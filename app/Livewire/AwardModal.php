<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ppmp;
use App\Models\Submission;
use App\Helpers\LogActivity;

class AwardModal extends Component
{
    public $ppmp;
    public $submissions;
    public $winner;

    public $selectedSubmissionId;
    public $award_date;
    public $remarks;

    public $showSuccess = false;
    public $awardedSupplierName; // ✅ store awarded supplier name

    protected $rules = [
        'selectedSubmissionId' => 'required|exists:submissions,id',
        'award_date' => 'required|date',
        'remarks' => 'nullable|string|max:1000',
    ];

    public function mount($ppmp, $submissions, $winner)
    {
        $this->ppmp = $ppmp;
        $this->submissions = $submissions;
        $this->winner = $winner;
        $this->award_date = now()->toDateString();
    }

    public function issueAward()
    {
        $this->validate();

        // ✅ fetch submission with supplier relation
        $submission = Submission::with('supplier', 'invitation', 'invitation.ppmp.items')->findOrFail($this->selectedSubmissionId);

        // update award fields
        $submission->update([
            'status' => 'awarded',
            'award_date' => $this->award_date,
            'remarks' => $this->remarks,
        ]);

        // ✅ update invitation if exists
        if ($submission->invitation) {
            $submission->invitation->update(['status' => 'awarded']);
        }

        // ✅ get supplier name
        $this->awardedSupplierName = $submission->supplier?->first_name ?? 'Unknown Supplier';

        $supplierName = $submission->supplier?->first_name ?? 'Unknown Supplier';
        $referenceNo  = $submission->invitation?->reference_no ?? 'N/A';

        // ✅ Log activity
        LogActivity::add(
            "awarded submission to '{$supplierName}' - Procurement '{$referenceNo}'",
        );

        // -------------- 🔥 CREATE AWARDED ITEMS & SKUs ----------------
        // loop through PPMP items and create awarded_items
        foreach ($submission->invitation?->ppmp?->items ?? [] as $item) {
            \App\Models\AwardedItem::create([
                'ppmp_id' => $submission->invitation->ppmp?->id, // ✅ correct PPMP ID
                'procurement_item_id' => $item->id,
                'invitation_id' => $submission->invitation_id,
                'supplier_id' => $submission->supplier_id,
                'sku' => 'SKU-' . date('Y') . '-' . ($submission->invitation->ppmp?->id ?? '0') . '-' . $item->id,
                'description' => $item->description,
                'qty' => $item->qty,
                'unit' => $item->unit,
                'unit_cost' => $item->unit_cost,
                'total_cost' => $item->total_cost,
            ]);
        }

        // ----------------------------------------------------------------

        // ✅ show success modal
        $this->dispatch('close-award-modal');
        $this->showSuccess = true;
    }

    public function closeSuccessModal()
    {
        $this->showSuccess = false;
    }

    public function render()
    {
        return view('livewire.award-modal');
    }
}
