<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ppmp;
use App\Models\Submission;
use App\Helpers\LogActivity;
use Livewire\WithPagination;

class BacSubmission extends Component
{
    use WithPagination;

    public $selectedPpmp = null;
    public $submissions = [];
    public $selectedSubmission = null;

    //evaluation scoring
    public $evaluationSubmission = null;
    public $technical_score, $financial_score, $total_score;

    protected $paginationTheme = 'tailwind'; 

    public function viewSubmission($submissionId)
    {
        $this->selectedSubmission = Submission::with('supplier')->find($submissionId);
    }

    public function showPpmp($ppmpId)
    {
        // Instead of searching in $this->ppmps (which no longer exists),
        // fetch the PPMP from the DB again if needed:
        $ppmp = Ppmp::with(['invitations', 'items'])
            ->find($ppmpId);

        if (!$ppmp) return;

        $this->selectedPpmp = $ppmp;

        // Flatten all invitations' submissions and filter out drafts
        $this->submissions = $ppmp->invitations
            ->flatMap(fn($inv) => $inv->submissions->where('status', '!=', 'draft'));

        // 🔑 Tell Alpine it’s safe to show now
        $this->dispatch('ppmpLoaded');
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
        $this->selectedSubmission = null;
    }

    public function evaluateSubmission($submissionId)
    {
        $submission = Submission::findOrFail($submissionId);

        $this->evaluationSubmission = $submission;
        $this->technical_score = $submission->technical_score;
        $this->financial_score = $submission->financial_score;
        $this->total_score     = $submission->total_score;
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['technical_score', 'financial_score'])) {
            $this->calculateTotalScore();
        }
    }

    private function calculateTotalScore()
    {
        $tech = floatval($this->technical_score ?? 0);
        $fin  = floatval($this->financial_score ?? 0);

        $this->total_score = ($tech * 0.7) + ($fin * 0.3);
    }


    public function saveEvaluation()
    {
        $this->validate([
            'technical_score' => 'required|numeric|min:0|max:100',
            'financial_score' => 'required|numeric|min:0|max:100',
        ]);

        $this->total_score = ($this->technical_score * 0.8) + ($this->financial_score * 0.2);

        $this->evaluationSubmission->update([
            'technical_score' => $this->technical_score,
            'financial_score' => $this->financial_score,
            'total_score'     => $this->total_score,
            'status'          => 'under_review', 
        ]);

        // ⭐ Add Activity Log
        $supplierName = $this->evaluationSubmission->supplier?->first_name ?? 'Unknown Supplier';
        $referenceNo  = $this->evaluationSubmission->invitation?->reference_no ?? 'N/A';

        LogActivity::add(
            "evaluated submission of '{$supplierName}' - of Procurement Bidding '{$referenceNo}' with a total score of {$this->total_score}",  
        );

        session()->flash('message', 'Evaluation saved successfully!');

        $this->dispatch('close-eval-modal');
        $this->evaluationSubmission = null;
    }

    //issue when pigination is on then the showPpmp function is fucking broken it cant fetch, you need to reload the page or else it doesnt fetch..
    public function render()
    {
        $query = Ppmp::where('status', 'approved')
            ->whereNotNull('mode_of_procurement')
            ->where('mode_of_procurement', '!=', '')
            ->whereHas('invitations', fn($q) => $q->where('status', '!=', 'pending'))
            ->with([
                'items',
                'invitations' => fn($q) => $q->where('status', '!=', 'pending')
                    ->with([
                        'suppliers',
                        'submissions' => fn($q) => $q->where('status', '!=', 'draft')
                            ->with(['supplier', 'items.procurementItem'])
                    ])
            ])
            ->orderBy('created_at', 'desc');

        $ppmps = $query->paginate(10);

        // Add counts on each ppmp in the current page
        $ppmps->getCollection()->transform(fn($ppmp) => $this->addCounts($ppmp));

        return view('livewire.bac-submission', [
            'ppmps' => $ppmps,
        ]);
    }
}
