<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function file($submissionId, $type)
    {
        $submission = Submission::with(['invitation.ppmp', 'supplier'])->findOrFail($submissionId);

        switch ($type) {
            case 'technical':
                $path   = $submission->technical_proposal_path;
                $prefix = 'Technical-Proposal';
                break;
            case 'financial':
                $path   = $submission->financial_proposal_path;
                $prefix = 'Financial-Proposal';
                break;
            case 'company':
                $path   = $submission->company_profile_path;
                $prefix = 'Company-Profile';
                break;
            default:
                abort(404, 'Invalid file type');
        }

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        // Supplier name (use first_name or fallback)
        $supplierName = $submission->supplier->first_name ?? $submission->supplier->name ?? 'Supplier';

        // Reference no from invitation
        $referenceNo = $submission->invitation->reference_no ?? 'N/A';

        // File extension
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        // Final filename format
        $fileName = "{$supplierName}_{$prefix}_{$referenceNo}.{$extension}";

        return Storage::disk('public')->download($path, $fileName);
    }

    public function view($submissionId, $type)
    {
        $submission = Submission::with(['invitation.ppmp', 'supplier'])->findOrFail($submissionId);

        switch ($type) {
            case 'technical':
                $path   = $submission->technical_proposal_path;
                $prefix = 'Technical-Proposal';
                break;
            case 'financial':
                $path   = $submission->financial_proposal_path;
                $prefix = 'Financial-Proposal';
                break;
            case 'company':
                $path   = $submission->company_profile_path;
                $prefix = 'Company-Profile';
                break;
            default:
                abort(404, 'Invalid file type');
        }

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        // Supplier name
        $supplierName = $submission->supplier->first_name ?? $submission->supplier->name ?? 'Supplier';

        // Reference no
        $referenceNo = $submission->invitation->reference_no ?? 'N/A';

        // Extension
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        // Filename
        $fileName = "{$supplierName}_{$prefix}_{$referenceNo}.{$extension}";

        // Stream file with inline disposition (view in browser, but nice filename)
        return response()->file(
            storage_path("app/public/{$path}"),
            [
                'Content-Disposition' => 'inline; filename="'.$fileName.'"'
            ]
        );
    }

}
