<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Invitation;
use App\Models\Submission;
use App\Models\SubmissionItem;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SupplierProposalSubmission extends Component
{
    use WithFileUploads;

    public $invitations = [];
    public $showModal = false;
    public $selectedInvitation = null; // Invitation model
    public $submission = null; // Submission model
    public $submissionItems = []; // collection for quotation mode
    public $unitPrices = []; // [submissionItemId => value]

    // bidding-specific fields
    public $bid_amount;
    public $technicalProposal; // Livewire file
    public $financialProposal;
    public $companyProfile;
    public $isCertified = false;

    // Add these public properties
    public $technicalProposalOriginalName;
    public $financialProposalOriginalName;
    public $companyProfileOriginalName;

    public $remarks;

    public function mount()
    {
        // On mount, only load invitations where the current supplier was explicitly invited.
        $this->loadInvitations();
    }

    // load only invitations that (1) are published and (2) this supplier was invited to
    public function loadInvitations()
    {
        // Load only invitations that are:
        // 1. Published
        // 2. Supplier was invited AND accepted

        $this->invitations = Invitation::where('status', 'published')
        ->whereHas('suppliers', function($q) {
            $q->where('supplier_id', Auth::id())
            ->where('invitation_supplier.response', 'accepted');
        })
        ->with(['ppmp', 'submissions' => function($q) {
            $q->where('supplier_id', Auth::id());
        }])
        ->orderBy('submission_deadline', 'asc')
        ->get();

    }

    public function removeTechnicalProposal()
    {
        $this->submission->technical_proposal_path = null;
        $this->submission->technical_proposal_original_name = null;
        $this->submission->save();
        $this->technicalProposalOriginalName = null;
    }

    public function removeFinancialProposal()
    {
        $this->submission->financial_proposal_path = null;
        $this->submission->financial_proposal_original_name = null;
        $this->submission->save();
        $this->financialProposalOriginalName = null;
    }

    public function removeCompanyProfile()
    {
        $this->submission->company_profile_path = null;
        $this->submission->company_profile_original_name = null;
        $this->submission->save();
        $this->companyProfileOriginalName = null;
    }


    public function openSubmission($invitationId)
    {
        $invitation = Invitation::with('ppmp.items')->findOrFail($invitationId);

        // ✅ Security check: block if current supplier isn’t in the invitation
        if (! $invitation->suppliers->contains(Auth::id())) {
            session()->flash('error', 'You are not invited to this event.');
            return;
        }

        // ✅ Deadline check: prevent late submissions
       // if (Carbon::now()->gt(Carbon::parse($invitation->submission_deadline))) {
       //     session()->flash('error', 'Submission deadline passed.');
      //      return;
       // }

        // ✅ Set invitation for modal
        $this->selectedInvitation = $invitation;

        // ✅ Create or load draft submission for this supplier
        $submission = Submission::firstOrCreate(
            ['invitation_id' => $invitation->id, 'supplier_id' => Auth::id()],
            ['status' => 'draft']
        );

        $this->submission = $submission;
        $this->remarks = $submission->remarks;

        // Populate original file names
        $this->technicalProposalOriginalName = $submission->technical_proposal_original_name;
        $this->financialProposalOriginalName = $submission->financial_proposal_original_name;
        $this->companyProfileOriginalName    = $submission->company_profile_original_name;

        // ✅ Handle Quotation Mode
        if ($invitation->ppmp->mode_of_procurement === 'quotation') {
            foreach ($invitation->ppmp->items as $item) {
                SubmissionItem::firstOrCreate(
                    ['submission_id' => $submission->id, 'procurement_item_id' => $item->id],
                    ['unit_price' => null, 'total_price' => null]
                );
            }
            $this->submissionItems = $submission->items()->with('procurementItem')->get();

            foreach ($this->submissionItems as $si) {
                $this->unitPrices[$si->id] = $si->unit_price;
            }
        } else {
            // ✅ Handle Bidding Mode
            $this->bid_amount = $submission->bid_amount;
            $this->technicalProposal = null;
            $this->financialProposal = null;
            $this->companyProfile = null;
        }

        // ✅ Finally open modal
        $this->showModal = true;
    }


    // Save draft for quotation (supplier can save progress)
    public function saveQuotationDraft()
    {
        $this->validateUnitPrices();

        foreach ($this->submissionItems as $si) {
            $price = $this->unitPrices[$si->id] ?? null;
            $qty = $si->procurementItem->qty ?? 1;
            $si->unit_price = $price;
            $si->total_price = is_null($price) ? null : ($price * $qty);
            $si->save();
        }

        $this->submission->remarks = $this->remarks;
        $this->submission->save();

        session()->flash('message', 'Draft saved');
        $this->loadInvitations();
    }

    protected function validateUnitPrices()
    {
        $rules = ['remarks' => 'nullable|string'];
        foreach ($this->submissionItems as $si) {
            $rules["unitPrices.{$si->id}"] = 'nullable|numeric|min:0';
        }
        $this->validate($rules);
    }

    // Submit quotation: require all items have unit price (adjust to your rules)
    public function submitQuotation()
    {
        // require all items to have price
        foreach ($this->submissionItems as $si) {
            $price = $this->unitPrices[$si->id] ?? null;
            if ($price === null) {
                $this->addError('unitPrices', 'All items must have a unit price before submission.');
                return;
            }
        }

        // compute totals (and save)
        foreach ($this->submissionItems as $si) {
            $price = $this->unitPrices[$si->id];
            $qty = $si->procurementItem->qty ?? 1;
            $si->unit_price = $price;
            $si->total_price = $price * $qty;
            $si->save();
        }

        $this->submission->status = 'submitted';
        $this->submission->submitted_at = now();
        $this->submission->remarks = $this->remarks;
        $this->submission->save();

        session()->flash('message', 'Quotation submitted');

        $this->showModal = false;
        $this->loadInvitations();
    }

    // bidding: save draft (amount + optional files)
    public function saveBiddingDraft()
    {
        $this->validate([
            'bid_amount' => 'nullable|numeric|min:0',
            'technicalProposal' => 'nullable|file|max:10240', // 10MB
            'financialProposal' => 'nullable|file|max:10240',
            'companyProfile' => 'nullable|file|max:10240',
            'remarks' => 'nullable|string',
        ]);

        // store files only if provided
        if ($this->technicalProposal) {
            $this->submission->technical_proposal_path = $this->technicalProposal->store('submissions/technical', 'public');
            $this->submission->technical_proposal_original_name = $this->technicalProposal->getClientOriginalName();
        }

        if ($this->financialProposal) {
            $this->submission->financial_proposal_path = $this->financialProposal->store('submissions/financial', 'public');
            $this->submission->financial_proposal_original_name = $this->financialProposal->getClientOriginalName();
        }

        if ($this->companyProfile) {
            $this->submission->company_profile_path = $this->companyProfile->store('submissions/company', 'public');
            $this->submission->company_profile_original_name = $this->companyProfile->getClientOriginalName();
        }

        $this->submission->bid_amount = $this->bid_amount;
        $this->submission->remarks = $this->remarks;
        $this->submission->save();

        session()->flash('message', 'Draft saved');
    }


    // bidding: final submission (require bid_amount and optionally files)
    public function submitBidding()
    {
        $this->validate([
           'bid_amount'        => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $abc = $this->selectedInvitation->ppmp->abc ?? 0;
                    if ($value > $abc) {
                        $fail("The bid amount cannot exceed the ABC (₱" . number_format($abc, 2) . ").");
                    }
                }
            ],
            'technicalProposal' => 'required|file|max:10240',
            'financialProposal' => 'required|file|max:10240',
            'companyProfile'    => 'required|file|max:10240',
            'isCertified'       => 'accepted', // ✅ checkbox must be true
        ]);

        // store files only if provided
        if ($this->technicalProposal) {
            $this->submission->technical_proposal_path = $this->technicalProposal->store('submissions/technical', 'public');
            $this->submission->technical_proposal_original_name = $this->technicalProposal->getClientOriginalName();
        }

        if ($this->financialProposal) {
            $this->submission->financial_proposal_path = $this->financialProposal->store('submissions/financial', 'public');
            $this->submission->financial_proposal_original_name = $this->financialProposal->getClientOriginalName();
        }

        if ($this->companyProfile) {
            $this->submission->company_profile_path = $this->companyProfile->store('submissions/company', 'public');
            $this->submission->company_profile_original_name = $this->companyProfile->getClientOriginalName();
        }

        $this->submission->bid_amount    = $this->bid_amount;
        $this->submission->is_certified  = true; // ✅ force TRUE when submitted
        $this->submission->status        = 'submitted';
        $this->submission->submitted_at  = now();
        $this->submission->save();

        session()->flash('message', 'Bid submitted');
        $this->showModal = false;
        $this->loadInvitations();
    }


    
    public function render()
    {
        return view('livewire.supplier-proposal-submission');
    }
}
