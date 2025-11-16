<?php

namespace App\Http\Controllers;

use App\Models\Ppmp;
use Barryvdh\DomPDF\Facade\Pdf;

class AwardController extends Controller
{
    public function generateAward($id)
    {
        $ppmp = Ppmp::with(['invitations.submissions.supplier'])->findOrFail($id);

        // ✅ Fetch the submission that was actually awarded
        $winner = $ppmp->invitations
            ->flatMap->submissions
            ->where('status', 'awarded')
            ->first();

        if (!$winner) {
            abort(404, 'No awarded supplier found.');
        }

        $pdf = Pdf::loadView('pdf.notice-of-award', [
            'ppmp'       => $ppmp,
            'winner'     => $winner,
            'award_date' => $winner->award_date ?? now()->format('F d, Y')
        ]);

        return $pdf->download("Notice_Of_Award_{$ppmp->invitations->first()->reference_no}.pdf");
    }

    public function generateQuotationAward($id)
    {
        $ppmp = Ppmp::with(['invitations.submissions.supplier', 'invitations.submissions.items'])
                    ->findOrFail($id);

        // ✅ Fetch the awarded submission (NOT recompute)
        $winner = $ppmp->invitations
            ->flatMap->submissions
            ->where('status', 'awarded')
            ->first();

        if (!$winner) {
            abort(404, 'No awarded supplier found.');
        }

        $pdf = Pdf::loadView('pdf.notice-of-award-quotation', [
            'ppmp'       => $ppmp,
            'winner'     => $winner,
            'award_date' => $winner->award_date ?? now()->format('F d, Y')
        ]);

        return $pdf->download("Notice_Of_Award_Quotation_{$ppmp->invitations->first()->reference_no}.pdf");
    }

}

