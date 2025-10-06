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
            // bidding: highest total_score wins
            $sorted = $this->submissions->sort(function ($a, $b) {
                $aScore = $a->total_score ?? 0;
                $bScore = $b->total_score ?? 0;

                if ($aScore == $bScore) {
                    $aBid = $a->bid_amount ?? PHP_INT_MAX;
                    $bBid = $b->bid_amount ?? PHP_INT_MAX;

                    if ($aBid == $bBid) {
                        $aDays = $a->delivery_days ?? PHP_INT_MAX;
                        $bDays = $b->delivery_days ?? PHP_INT_MAX;

                        return $aDays <=> $bDays; // earlier delivery wins if tie on score & bid
                    }

                    return $aBid <=> $bBid; // lower bid wins if tie on score
                }

                return $bScore <=> $aScore; // higher score wins
            });
        } else {
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
            'ppmp'        => $this->ppmp,
            'winner'      => $this->winner,
            'awardedSubmission' => $this->awardedSubmission,
        ]);
    }
}
