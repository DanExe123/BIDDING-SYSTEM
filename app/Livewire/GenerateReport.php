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

    // computed winner (kept in render but also available to Blade)
    public $winner;

    public function mount($ppmpId)
    {
        $this->ppmpId = $ppmpId;

        $ppmp = Ppmp::with([
            'invitations.submissions' => fn($q) => $q->where('status', '!=', 'draft')
                ->with('supplier')
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

        // Sort: first by total_score desc, then by bid_amount asc (lower bid wins on tie)
        $sorted = $this->submissions->sort(function ($a, $b) {
            // ensure nulls don't break
            $aScore = $a->total_score ?? 0;
            $bScore = $b->total_score ?? 0;
            if ($aScore == $bScore) {
                $aBid = $a->bid_amount ?? PHP_INT_MAX;
                $bBid = $b->bid_amount ?? PHP_INT_MAX;
                return $aBid <=> $bBid; // lower bid earlier
            }
            return $bScore <=> $aScore; // higher score earlier
        });

        $this->winner = $sorted->first();
        return $this->winner;
    }

    public function issueAward($submissionId)
    {
        // validate modal inputs (award_date required)
        $this->validate([
            'award_date' => 'required|date',
            'remarks'    => 'nullable|string|max:1000',
        ]);

        $submission = Submission::findOrFail($submissionId);

        // Optional safety: ensure submission belongs to this PPMP
        $invitation = $submission->invitation;
        if (! $invitation || $invitation->ppmp_id != $this->ppmpId) {
            session()->flash('error', 'Invalid submission for this report.');
            return;
        }

        // mark awarded and save remarks/award_date if your schema supports it
        $submission->status = 'awarded';
        // if you have columns for awarded_at / award_date, set them:
        if (in_array('awarded_at', $submission->getFillable())) {
            $submission->awarded_at = $this->award_date;
        }
        // store remarks into submission->remarks if desired
        $submission->remarks = $this->remarks;
        $submission->save();

        session()->flash('message', 'Award issued successfully to ' . $submission->supplier->first_name);

        // redirect to BAC procurement workflow page — replace route name if different
        return redirect()->route('bac-procurement-workflow');
    }

    public function render()
    {
        // keep ppmp fresh (in case)
        $this->ppmp = Ppmp::with('invitations.submissions.supplier')->findOrFail($this->ppmpId);

        // recompute submissions collection and winner
        $this->submissions = $this->ppmp->invitations->flatMap->submissions;
        $this->computeWinner();

        return view('livewire.generate-report', [
            'submissions' => $this->submissions,
            'ppmp' => $this->ppmp,
            'winner' => $this->winner,
        ]);
    }
}
