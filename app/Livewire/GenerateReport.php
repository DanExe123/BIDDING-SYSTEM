<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ppmp;
use App\Models\Submission;

class GenerateReport extends Component
{
    public $ppmpId;
    public $submissions = [];
    public $ppmp;

    // modal form props
    public $award_date;
    public $remarks;
    public $awardedSubmission; // ✅ holds awarded submission if any



    // computed winner (kept in render but also available to Blade)
    public $winner;
    public $selectedSubmissionId; // BAC's manual choice

    public function mount($ppmpId)
    {
        $this->ppmpId = $ppmpId;

        $ppmp = Ppmp::with([
            'invitations.submissions' => fn($q) => $q->where('status', '!=', 'draft')
                ->with(['supplier', 'items'])
        ])->findOrFail($ppmpId);

        $this->ppmp = $ppmp;

        // flatten submissions (collection)
        $this->submissions = $ppmp->invitations->flatMap->submissions;

        $this->award_date = now()->toDateString();
    }

    private function computeWinner()
    {
        if (empty($this->submissions) || $this->submissions->isEmpty()) {
            $this->winner = null;
            return null;
        }

        if ($this->ppmp->mode_of_procurement === 'bidding') {
            // Step 1: find max and min bids across all submissions
        $maxBid = $this->submissions->max(fn($s) => $s->bid_amount ?? 0) ?: 1;
        $minBid = $this->submissions->min(fn($s) => $s->bid_amount ?? 0) ?: 1;
        $maxDays = $this->submissions->max(fn($s) => $s->delivery_days ?? 0) ?: 1;

        // Step 2: compute weighted score for each submission
        foreach ($this->submissions as $s) {
            $score = $s->total_score ?? 0;
            
            // Lower bid → higher score (0-100)
            $bidScore = 100 * ($maxBid - ($s->bid_amount ?? $maxBid)) / ($maxBid - $minBid ?: 1);
            
            // Lower delivery_days → higher score (0-100)
            $daysScore = 100 * (1 - ($s->delivery_days ?? $maxDays) / $maxDays);

            // Weighted total: 40% total_score, 40% bid, 20% delivery_days
            $s->weighted_total = $score * 0.80 + $bidScore * 0.15 + $daysScore * 0.05;
        }

        // Step 3: sort descending, highest weighted_total wins
        $sorted = $this->submissions->sortByDesc('weighted_total');
        $this->winner = $sorted->first();

        }
        else {
            // quotation: lower total price wins, tie-break with delivery days
            $sorted = $this->submissions->sort(function ($a, $b) {
                $aTotal = $a->items->sum(fn($i) => ($i->unit_price ?? 0) * ($i->qty ?? 1));
                $bTotal = $b->items->sum(fn($i) => ($i->unit_price ?? 0) * ($i->qty ?? 1));

                if ($aTotal == $bTotal) {
                    $aDays = $a->delivery_days ?? PHP_INT_MAX;
                    $bDays = $b->delivery_days ?? PHP_INT_MAX;

                    return $aDays <=> $bDays; // earlier delivery wins
                }

                return $aTotal <=> $bTotal; // lower price wins
            });
        }

        $this->winner = $sorted->first();
        return $this->winner;
    }

    private function buildScoreMatrix()
{
    $rows = collect();

    foreach ($this->submissions as $submission) {
        if ($submission->status === 'rejected') {
            continue;
        }

        $tech = floatval($submission->technical_score ?? 0);
        $fin  = floatval($submission->financial_score ?? 0);

        $weightedTech = round($tech * 0.80, 2);
        $weightedFin  = round($fin * 0.20, 2);
        $combined     = round($weightedTech + $weightedFin, 2);

        $rows->push([
            'bidder'        => $submission->supplier->first_name ?? 'N/A',
            'tech_weighted' => $weightedTech,
            'fin_weighted'  => $weightedFin,
            'combined'      => $combined,
        ]);
    }

    // Rank by combined score (highest wins)
    return $rows
        ->sortByDesc('combined')
        ->values()
        ->map(function ($row, $index) {
            $row['rank'] = '#' . ($index + 1);
            return $row;
        });
}


    public function render()
    {
        // keep ppmp fresh (in case)
        $this->ppmp = Ppmp::with('invitations.submissions.supplier')->findOrFail($this->ppmpId);

        // recompute submissions collection and winner
        $this->submissions = $this->ppmp->invitations->flatMap->submissions;
        $this->computeWinner();

        $this->awardedSubmission = $this->submissions->firstWhere('status', 'awarded');

        return view('livewire.generate-report', [
            'submissions' => $this->submissions,
            'scoreMatrix' => $this->buildScoreMatrix(),
            'ppmp'        => $this->ppmp,
            'winner'      => $this->winner,
            'awardedSubmission' => $this->awardedSubmission,
        ]);
    }
}
