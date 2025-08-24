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

    public $remarks;

    public function mount()
    {
        $this->loadInvitations();
    }

    // load only invitations that (1) are published and (2) this supplier was invited to
    public function loadInvitations()
    {
        $this->invitations = Invitation::where('status','published')
            ->whereHas('suppliers', function($q) {
                $q->where('supplier_id', Auth::id());
            })
            ->with('ppmp')
            ->orderBy('submission_deadline','asc')
            ->get();
    }

    // open the submission modal for a specific invitation
    public function openSubmission($invitationId)
    {
        $invitation = Invitation::with('ppmp.items')->findOrFail($invitationId);

        // security: ensure supplier is invited
        if (! $invitation->suppliers->contains(Auth::id())) {
            $this->dispatchBrowserEvent('notify', ['type'=>'error','message'=>'You are not invited to this event.']);
            return;
        }

        // ensure not past deadline
        if (Carbon::now()->gt(Carbon::parse($invitation->submission_deadline))) {
            $this->dispatchBrowserEvent('notify', ['type'=>'error','message'=>'Submission deadline passed.']);
            return;
        }

        $this->selectedInvitation = $invitation;

        // get or create submission record (draft)
        $submission = Submission::firstOrCreate(
            ['invitation_id' => $invitation->id, 'supplier_id' => Auth::id()],
            ['status' => 'draft']
        );

        $this->submission = $submission;
        $this->remarks = $submission->remarks;

        if ($invitation->ppmp->mode_of_procurement === 'quotation') {
            // ensure submission_items exist for each procurement item
            foreach ($invitation->ppmp->items as $item) {
                SubmissionItem::firstOrCreate(
                    ['submission_id' => $submission->id, 'procurement_item_id' => $item->id],
                    ['unit_price' => null, 'total_price' => null]
                );
            }
            $this->submissionItems = $submission->items()->with('procurementItem')->get();
            // populate unitPrices map
            foreach ($this->submissionItems as $si) {
                $this->unitPrices[$si->id] = $si->unit_price;
            }
        } else {
            // bidding: load current values
            $this->bid_amount = $submission->bid_amount;
            // files remain null (user can upload to replace)
            $this->technicalProposal = null;
            $this->financialProposal = null;
            $this->companyProfile = null;
        }

        $this->showModal = true;
    }

    // Save draft for quotation (supplier can save progress)
    public function saveQuotationDraft()
    {
        $this->validateUnitPrices();

        foreach ($this->submissionItems as $si) {
            $price = $this->unitPrices[$si->id] ?? null;
            $qty = $si->procurementItem->quantity ?? 1;
            $si->unit_price = $price;
            $si->total_price = is_null($price) ? null : ($price * $qty);
            $si->save();
        }

        $this->submission->remarks = $this->remarks;
        $this->submission->save();

        $this->dispatchBrowserEvent('notify', ['type'=>'success','message'=>'Draft saved']);
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
            $qty = $si->procurementItem->quantity ?? 1;
            $si->unit_price = $price;
            $si->total_price = $price * $qty;
            $si->save();
        }

        $this->submission->status = 'submitted';
        $this->submission->submitted_at = now();
        $this->submission->remarks = $this->remarks;
        $this->submission->save();

        $this->dispatchBrowserEvent('notify', ['type'=>'success','message'=>'Quotation submitted']);
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

        // store files only if provided (keep existing if not)
        if ($this->technicalProposal) {
            $this->submission->technical_proposal_path = $this->technicalProposal->store('submissions/technical', 'public');
        }
        if ($this->financialProposal) {
            $this->submission->financial_proposal_path = $this->financialProposal->store('submissions/financial', 'public');
        }
        if ($this->companyProfile) {
            $this->submission->company_profile_path = $this->companyProfile->store('submissions/company', 'public');
        }

        $this->submission->bid_amount = $this->bid_amount;
        $this->submission->remarks = $this->remarks;
        $this->submission->save();

        $this->dispatchBrowserEvent('notify', ['type'=>'success','message'=>'Draft saved']);
    }

    // bidding: final submission (require bid_amount and optionally files)
    public function submitBidding()
    {
        $this->validate([
            'bid_amount' => 'required|numeric|min:0',
            'technicalProposal' => 'nullable|file|max:10240',
            'financialProposal' => 'nullable|file|max:10240',
            'companyProfile' => 'nullable|file|max:10240',
        ]);

        if ($this->technicalProposal) {
            $this->submission->technical_proposal_path = $this->technicalProposal->store('submissions/technical', 'public');
        }
        if ($this->financialProposal) {
            $this->submission->financial_proposal_path = $this->financialProposal->store('submissions/financial', 'public');
        }
        if ($this->companyProfile) {
            $this->submission->company_profile_path = $this->companyProfile->store('submissions/company', 'public');
        }

        $this->submission->bid_amount = $this->bid_amount;
        $this->submission->status = 'submitted';
        $this->submission->submitted_at = now();
        $this->submission->save();

        $this->dispatchBrowserEvent('notify', ['type'=>'success','message'=>'Bid submitted']);
        $this->showModal = false;
        $this->loadInvitations();
    }

    public function render()
    {
        return view('livewire.supplier-proposal-submission');
    }
}
