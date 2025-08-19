<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ppmp;
use App\Models\BidInvitation;
use App\Models\User;
use App\Models\SupplierCategory;
use Illuminate\Support\Facades\Auth;

class BacBidInvitation extends Component
{
    public $ppmps = [];
    public $selectedPpmp = null;
    // form fields
    public $bidTitle, $bidReference, $approvedBudget, $sourceOfFunds;
    public $preBidDate, $submissionDeadline, $bidDocuments;

    // supplier invite scope
    public $inviteScope = 'all'; // all | category | specific
    public $supplierCategoryId = null;
    public $selectedSuppliers = [];

    public $categories = [];
    public $suppliers = [];

    public function mount()
    {
        // Fetch PPMPs that are approved and don't have any bid invitations
        // with status 'published', 'awarded', or 'closed'
        $this->ppmps = Ppmp::where('status', 'approved') // only approved PPMPs
            ->where('mode_of_procurement', 'bidding')
            ->whereDoesntHave('bidInvitations', function ($query) {
                // Exclude PPMPs that already have invitations that are published, awarded, or closed
                $query->whereIn('status', ['published', 'awarded', 'closed']);
            })
            ->with('items') // eager load items
            ->orderBy('created_at', 'desc')
            ->get();

        $this->categories = SupplierCategory::all();

        // FIXED: no supplierProfile, just supplierCategory
        $this->suppliers = User::role('supplier')->with('supplierCategory')->get();
    }

    public function showPpmp($ppmpId)
    {
        $this->selectedPpmp = Ppmp::find($ppmpId);

        $this->bidTitle = $this->selectedPpmp->project_title;
        $this->approvedBudget = $this->selectedPpmp->abc;
        $this->sourceOfFunds = $this->selectedPpmp->implementing_unit;

        // pad PPMP id to 4 digits (e.g. 3 -> 0003)
        $paddedId = str_pad($ppmpId, 4, '0', STR_PAD_LEFT);

        // Format: BID-2025-PPMP3-0003
        $this->bidReference = 'BID-' . date('Y') . '-PPMP' . $ppmpId . '-' . $paddedId;
    }

    public function closeModal()
    {
        $this->reset(['selectedPpmp']);
    } 

    // Separate rules method
    protected function rules()
    {
        return [
            'selectedPpmp'        => 'required',
            'bidTitle'            => 'required|string|max:255',
            'bidReference'        => 'required|string|max:255|unique:bid_invitations,bid_reference',
            'approvedBudget'      => 'required|numeric|min:0',
            'sourceOfFunds'       => 'required|string|max:255',
            'preBidDate'          => 'required|date',
            'submissionDeadline'  => 'required|date|after_or_equal:preBidDate',
            'inviteScope'         => 'required|in:all,category,specific',
            'supplierCategoryId'  => 'required_if:inviteScope,category|nullable|exists:supplier_categories,id',
            'selectedSuppliers'   => 'required_if:inviteScope,specific|array',
            'selectedSuppliers.*' => 'exists:users,id',
        ];
    }

    public function saveInvitation()
    {
        // Validate form inputs
        $this->validate();

        // Create the bid invitation
        $bid = BidInvitation::create([
            'ppmp_id'             => $this->selectedPpmp->id,
            'bid_title'           => $this->bidTitle,
            'bid_reference'       => $this->bidReference,
            'approved_budget'     => $this->approvedBudget,
            'source_of_funds'     => $this->sourceOfFunds,
            'pre_bid_date'        => $this->preBidDate,
            'submission_deadline' => $this->submissionDeadline,
            'bid_documents'       => $this->bidDocuments,
            'invite_scope'        => $this->inviteScope,
            'supplier_category_id'=> $this->supplierCategoryId,
            'created_by'          => Auth::id(),
            'status'              => 'published',
        ]);

        // Attach suppliers based on invite scope using pivot table
        switch ($this->inviteScope) {
            case 'all':
                $suppliers = User::role('supplier')->pluck('id');
                break;

            case 'category':
                $suppliers = User::role('supplier')
                    ->where('supplier_category_id', $this->supplierCategoryId)
                    ->pluck('id');
                break;

            case 'specific':
                $suppliers = $this->selectedSuppliers ?? [];
                break;

            default:
                $suppliers = [];
        }

        // Sync suppliers to pivot table
        $bid->suppliers()->sync($suppliers);

        // Flash success message and close modal
        session()->flash('message', 'Bid Invitation Published.');
        $this->closeModal();
        $this->dispatch('close-modal');
    }

    public function render()
    {
        return view('livewire.bac-bid-invitation');
    }
}
