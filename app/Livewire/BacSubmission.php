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
    public $technical_score, $financial_score, $total_score, $weighted_technical_score;

    public $tech_effectiveness = null;
    public $tech_methodology = null;
    public $tech_team = null;
    public $tech_sustainability = null;

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

        $this->tech_effectiveness   = $submission->tech_effectiveness;
        $this->tech_methodology     = $submission->tech_methodology;
        $this->tech_team            = $submission->tech_team;
        $this->tech_sustainability  = $submission->tech_sustainability;
        $this->weighted_technical_score = $submission->weighted_technical_score;

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

    public function generateTechnicalScore()
    {
        $this->validate([
            'tech_effectiveness'  => 'required|integer|min:0|max:5',
            'tech_methodology'    => 'required|integer|min:0|max:5',
            'tech_team'           => 'required|integer|min:0|max:5',
            'tech_sustainability' => 'required|integer|min:0|max:5',
        ]);

        // FIGURE IX — Initial Technical Score (WEIGHTED, RAW)
        $this->weighted_technical_score =
            ($this->tech_effectiveness  * 50) +
            ($this->tech_methodology    * 25) +
            ($this->tech_team           * 15) +
            ($this->tech_sustainability * 10);

        $this->calculateTotalScore();
    }

    public function generateTechnicalScoreButton()
    {
        if (!$this->evaluationSubmission) return;

        // Ensure weighted_technical_score exists
        $this->generateTechnicalScore(); // calculates weighted_technical_score

        // Get the highest weighted score among all submissions for this PPMP
        $highestWeighted = $this->submissions->max('weighted_technical_score') ?? 1;

        // Technical Score formula
        $this->technical_score = ($this->weighted_technical_score / $highestWeighted) * 100;

        // Make sure it doesn't exceed 100
        $this->technical_score = min($this->technical_score, 100);

        $this->calculateTotalScore();
    }

    /* 
    private function calculateTotalScore()
    {
        $tech = floatval($this->technical_score ?? 0);
        $fin  = floatval($this->financial_score ?? 0);

        $this->total_score = ($tech * 0.8) + ($fin * 0.2);
    }*/

    private function calculateTotalScore()
    {
        $tech = floatval($this->technical_score ?? 0);
        $fin  = floatval($this->financial_score ?? 0);

        // FINANCIAL SCORE (IMAGE FORMULA)
        $lowestBid = $this->getLowestBidAmount();

        if ($lowestBid > 0 && $this->evaluationSubmission) {
            $bidAmount = $this->evaluationSubmission->bid_amount;

            // (lowest bid / bidder bid) × 100
            $comparativeScore = ($lowestBid / $bidAmount) * 100;

            // weighted financial score (20%)
            $this->financial_score = $comparativeScore;
        }

        $this->total_score = ($tech * 0.8) + ($this->financial_score * 0.2);
    }

    private function getLowestBidAmount()
    {
        if (!$this->selectedPpmp || $this->submissions->isEmpty()) {
            return 0;
        }

        return (float) $this->submissions->min('bid_amount');
    }

    public function updatedTechnicalScore()
    {
        if ($this->evaluationSubmission) {
            $this->calculateTotalScore();
        }
    }


    public function saveRawTechnicalScores()
    {
        $this->validate([
            'tech_effectiveness'  => 'required|integer|min:0|max:5',
            'tech_methodology'    => 'required|integer|min:0|max:5',
            'tech_team'           => 'required|integer|min:0|max:5',
            'tech_sustainability' => 'required|integer|min:0|max:5',
        ]);

        if (!$this->evaluationSubmission) {
            return;
        }

        // JUST ADD. NOTHING ELSE.
        $rawTechnicalScore =
            ($this->tech_effectiveness  * 50) +
            ($this->tech_methodology    * 25) +
            ($this->tech_team           * 15) +
            ($this->tech_sustainability * 10);

        $this->evaluationSubmission->update([
            'tech_effectiveness'  => $this->tech_effectiveness,
            'tech_methodology'    => $this->tech_methodology,
            'tech_team'           => $this->tech_team,
            'tech_sustainability' => $this->tech_sustainability,
            'weighted_technical_score'     => $rawTechnicalScore,
        ]);

        session()->flash('message', 'Raw technical score saved.');
    }

    public function updatedFinancialScore()
    {
        if ($this->evaluationSubmission) {
            $this->calculateTotalScore();
        }
    }

    public function saveEvaluation()
    {
         $this->validate([
        'technical_score' => 'required|numeric|min:0|max:100',
        // ✅ GIS: Financial auto-calculated - NO validation needed
        ]);

        // ✅ Calculate once here (GIS logic)
        $this->calculateTotalScore();

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