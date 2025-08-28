<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ppmp;

class BacSubmission extends Component
{
    public $ppmps = [];
    public $selectedPpmp = null;
    public $submissions = [];

    public function mount()
    {
        // Load all approved PPMPs with published invitations and their submissions
        $this->loadPpmps();
    }

    private function loadPpmps()
    {
        $this->ppmps = Ppmp::where('status', 'approved') // Only PPMPs that are approved
            ->whereNotNull('mode_of_procurement')        // Exclude PPMPs without a procurement mode
            ->where('mode_of_procurement', '!=', '')    // Exclude empty procurement mode strings
            ->whereHas('invitations', fn($q) => 
                $q->where('status', 'published')        // Only PPMPs that have at least one published invitation
            )
            ->with([
                'items',                                 // Eager load PPMP items
                'invitations' => fn($q) => $q->where('status', 'published') // Only published invitations
                    ->with([
                        'suppliers',                     // Eager load suppliers of the invitation
                        'submissions' => fn($q) => $q->where('status', '!=', 'draft') // Only non-draft submissions
                            ->with(['supplier', 'items.procurementItem']) // Load submission supplier and related items
                    ])
            ])
            ->orderBy('created_at', 'desc')             // Order PPMPs by newest first
            ->get()
            ->map(fn($ppmp) => $this->addCounts($ppmp)); // Add totalInvited and totalSubmitted counts
    }

    public function showPpmp($ppmpId)
    {
        $ppmp = $this->ppmps->firstWhere('id', $ppmpId); // Find the PPMP from the already loaded collection
        if (!$ppmp) return; // Exit if not found

        $this->selectedPpmp = $ppmp;

        // Flatten all invitations' submissions and filter out drafts
        $this->submissions = $ppmp->invitations
            ->flatMap(fn($inv) => $inv->submissions->where('status', '!=', 'draft'));
    }

    private function addCounts($ppmp)
    {
        $invitation = $ppmp->invitations->last(); // Get the latest invitation

        $ppmp->totalInvited = $invitation 
            ? $invitation->suppliers->count() // Count of suppliers invited for the latest invitation
            : 0;

        $ppmp->totalSubmitted = $invitation
            ? $invitation->submissions->where('status', '!=', 'draft')->count() // Count only non-draft submissions
            : 0;

        return $ppmp;
    }


    public function closeModal()
    {
        $this->selectedPpmp = null;
        $this->submissions = [];
    }

    public function render()
    {
        return view('livewire.bac-submission');
    }
}
