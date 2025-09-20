<?php

namespace App\Http\Controllers;

use App\Models\Ppmp;
use Barryvdh\DomPDF\Facade\Pdf;

class AwardController extends Controller
{
    public function generateAward($id)
    {
        $ppmp = Ppmp::with(['invitations.submissions.supplier'])->findOrFail($id);

        // Compute winner same as Livewire GenerateReport
        $submissions = $ppmp->invitations->flatMap->submissions;

        $winner = $submissions->sort(function ($a, $b) {
            $aScore = $a->total_score ?? 0;
            $bScore = $b->total_score ?? 0;

            if ($aScore == $bScore) {
                $aBid = $a->bid_amount ?? PHP_INT_MAX;
                $bBid = $b->bid_amount ?? PHP_INT_MAX;
                return $aBid <=> $bBid; // lower bid wins tie
            }
            return $bScore <=> $aScore; // higher score wins
        })->first();

        $pdf = Pdf::loadView('pdf.notice-of-award', [
            'ppmp'       => $ppmp,
            'winner'     => $winner,
            'award_date' => now()->format('F d, Y')
        ]);

        return $pdf->download("Notice_Of_Award_{$ppmp->invitations->first()->reference_no}.pdf");
    }
}

