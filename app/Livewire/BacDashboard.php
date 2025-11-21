<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ppmp;
use App\Models\User;
use App\Models\Invitation;
use App\Models\Submission;
use Carbon\Carbon;

class BacDashboard extends Component
{
    public $activeProcurementsCount;
    public $activeProcurementsLastMonth;
    public $openBidsCount;  
    public $rfqCount;  
    public $pendingApprovalsCount;
    public $registeredSuppliersCount;
    public $registeredSuppliersThisMonth;
    public $openBidsEndingToday;
    public $rfqEndingToday;

    public $rfqSubmissionCount;
    public $rfqEvaluationCount;
    public $bidSubmissionCount;
    public $bidEvaluationCount;
    public $contractAwardCount;

    public $recentActivities = [];
    public $publicBidNotices;
    public $biddingData = [];
public $quotationData = [];

    public function mount()
    {
        // Active Procurements (example: approved)
        $this->activeProcurementsCount = Ppmp::where('status', 'approved')->count();

        // Active Procurements (last month only)
        $this->activeProcurementsLastMonth = Ppmp::where('status', 'approved')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        // Open Bids (approved, bidding mode, has invitations)
        $this->openBidsCount = Ppmp::where('status', 'approved')
            ->where('mode_of_procurement', 'bidding')
            ->whereHas('invitations') // must have invitations
            ->count();
        //active request for quotation
        $this->rfqCount = Ppmp::where('status', 'approved')
            ->where('mode_of_procurement', 'quotation')
            ->whereHas('invitations') // must have invitations
            ->count();

          // Open Bids ending today
        $this->openBidsEndingToday = Invitation::whereDate('submission_deadline', Carbon::today())
            ->whereHas('ppmp', function ($q) {
                $q->where('status', 'approved')
                  ->where('mode_of_procurement', 'bidding');
            })
            ->count();

        // Open rfq ending today
        $this->rfqEndingToday = Invitation::whereDate('submission_deadline', Carbon::today())
            ->whereHas('ppmp', function ($q) {
                $q->where('status', 'approved')
                  ->where('mode_of_procurement', 'qutation');
            })
            ->count();

        // Pending Approvals
        $this->pendingApprovalsCount = Ppmp::where('status', 'pending')->count();

        // Registered Suppliers (Spatie role = supplier)
        $this->registeredSuppliersCount = User::role('supplier')->count();

         // Registered Suppliers (this month)
        $this->registeredSuppliersThisMonth = User::role('supplier')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // RFQ Submission
        $this->rfqSubmissionCount = Submission::whereHas('invitation.ppmp', function ($q) {
            $q->where('mode_of_procurement', 'quotation');
        })->count();

        // RFQ Evaluation
        $this->rfqEvaluationCount = Submission::where('status', 'under_review')
            ->whereHas('invitation.ppmp', function ($q) {
                $q->where('mode_of_procurement', 'quotation');
            })
            ->count();

        // Bid Submission
        $this->bidSubmissionCount = Submission::whereHas('invitation.ppmp', function ($q) {
            $q->where('mode_of_procurement', 'bidding');
        })->count();

        // Bid Evaluation
        $this->bidEvaluationCount = Submission::where('status', 'under_review')
            ->whereHas('invitation.ppmp', function ($q) {
                $q->where('mode_of_procurement', 'bidding');
            })
            ->count();

        // Contract Award (all awarded submissions)
        $this->contractAwardCount = Submission::where('status', 'awarded')->count();


        $yesterday = Carbon::now()->subDay(); // 24 hrs ago

            // Purchaser requests (PPMPs)
            $recentPpmps = Ppmp::with('requester')
                ->where('created_at', '>=', $yesterday)
                ->get()
                ->map(function($ppmp) {
                    return [
                        'type' => 'ppmp',
                        'user' => $ppmp->requester->first_name ?? 'Unknown User',
                        'message' => "submitted PPMP for <strong>\"{$ppmp->project_title}\"</strong>",
                        'time' => $ppmp->created_at,
                    ];
                });

            // Invitations
            $recentInvitations = Invitation::with('createdBy')
                ->where('created_at', '>=', $yesterday)
                ->get()
                ->map(function($invitation) {
                    $referenceNo = $invitation->reference_no ?? 'N/A';
                    return [
                        'type' => 'invitation',
                        'user' => 'You',
                        'message' => "created Invitation: <strong>\"{$invitation->title} - {$referenceNo}\"</strong>",
                        'time' => $invitation->created_at,
                    ];
                });

            // Submissions
            $recentSubmissions = Submission::with(['supplier', 'invitation.ppmp']) 
                ->where('created_at', '>=', $yesterday)
                ->get()
                ->map(function($submission) {
                    $supplierName = $submission->supplier->first_name ?? 'Unknown User';
                    $referenceNo  = $submission->invitation->reference_no ?? 'N/A';
                    $projectTitle = optional($submission->invitation->ppmp)->project_title ?? 'Unknown Project';
                    $mode         = strtolower(optional($submission->invitation->ppmp)->mode_of_procurement ?? '');

                    // ✅ Choose wording based on procurement mode
                    $action = $mode === 'quotation'
                        ? 'submitted a Quotation'
                        : 'submitted a Bid';

                    return [
                        'type' => 'submission',
                        'user' => $supplierName,
                        'message' => "{$action} for <strong>\"{$projectTitle} - {$referenceNo}\"</strong>",
                        'time' => $submission->created_at,
                    ];
                });



            // Awarded Submissions (use award_date, not updated_at)
            // -------------------------
            $recentAwards = Submission::with(['supplier', 'invitation.ppmp'])
                ->where('status', 'awarded')                 // only awarded submissions
                ->whereNotNull('award_date')                 // must have award_date
                ->where('award_date', '>=', $yesterday)     // within last 24 hours
                ->get()
                ->map(function ($submission) {
                    // supplier full name (fallback if fields missing)
                    $supplierName = trim(
                        ($submission->supplier->first_name ?? '') . ' ' .
                        ($submission->supplier->last_name ?? '')
                    ) ?: ($submission->supplier->username ?? 'Unknown Supplier');

                    $projectTitle = optional($submission->invitation->ppmp)->project_title ?? 'Unknown Project';
                    $mode         = optional($submission->invitation->ppmp)->mode_of_procurement ?? 'Unknown Mode';

                    return [
                        'type'    => 'award',
                        'user'    => 'BAC Sec', // who performed the award (adjust if needed)
                        'message' => "awarded <strong>\"{$supplierName}\"</strong> - {$projectTitle} ({$mode})",
                        'time'    => Carbon::parse($submission->award_date), // ensure Carbon for diffForHumans()
                    ];
                });

            // -------------------------
            // Merge into recentActivities with other arrays you already have
            // (example assumes you already have $recentPpmps, $recentInvitations, $recentSubmissions)
            // -------------------------
            $this->recentActivities = collect($recentPpmps)
                ->merge(collect($recentInvitations))
                ->merge(collect($recentSubmissions))
                ->merge(collect($recentAwards))
                ->sortByDesc('time')
                ->values();


            // Public Bid Notices (latest invitations with PPMP)
            $this->publicBidNotices = Invitation::with('ppmp')
                ->whereHas('ppmp', function ($q) {
                    $q->where('mode_of_procurement', 'bidding')
                    ->where('status', 'approved');
                })
                ->latest()
                ->take(5)
                ->get();


                // 12 months
    $months = collect(range(1, 12));

    // BIDDING
    $this->biddingData = $months->map(function($m) {
        return Ppmp::where('status', 'approved')
            ->where('mode_of_procurement', 'bidding')
            ->whereMonth('created_at', $m)
            ->count();
    })->toArray();

    // QUOTATION
    $this->quotationData = $months->map(function($m) {
        return Ppmp::where('status', 'approved')
            ->where('mode_of_procurement', 'quotation')
            ->whereMonth('created_at', $m)
            ->count();
    })->toArray();
    }

    public function render()
    {
        return view('livewire.bac-dashboard');
    }
}
