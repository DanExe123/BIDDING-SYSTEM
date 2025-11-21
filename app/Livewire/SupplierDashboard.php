<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invitation;
use App\Models\Submission;
use App\Models\Ppmp;
use App\Models\InvitationSupplier;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SupplierDashboard extends Component
{
    public $announcements = [];
    public $recentActivities = [];
    public $activeProcurements = [];
    public $invitationCount = 0;


    public function mount()
    {
        $supplierId = Auth::id();

        // ✅ Invitations still pending (like your original)
        $invitations = Invitation::with('ppmp')
            ->whereHas('ppmp', function ($q) {
                $q->where('status', 'approved');
            })
            ->whereHas('suppliers', function ($q) use ($supplierId) {
                $q->where('users.id', $supplierId)
                ->where(function($q2) {
                    $q2->whereNull('invitation_supplier.response')
                        ->orWhere('invitation_supplier.response', 'pending');
                });
            })
            ->latest()
            ->take(5)
            ->get();

        $this->invitationCount = $invitations->count();


        // ✅ Announcements from invitations
        $invitationAnnouncements = $invitations->map(function ($inv) {
            $mode = strtolower($inv->ppmp->mode_of_procurement ?? '');
            $project = $inv->ppmp->project_title ?? 'Unknown Project';
            $abc = $inv->ppmp->abc ?? 0;

            if ($mode === 'quotation') {
                $message = "You have a request for quotation: \"{$project}\" <strong>(₱" . number_format($abc, 2) . ")</strong>";
            } else {
                $message = "You are invited to {$mode} project \"{$project}\" <strong>(₱" . number_format($abc, 2) . ")</strong>";
            }

            return [
                'message' => $message,
                'time'    => $inv->created_at,
                'route'   => route('supplier-invitations'), // 👈 default route for invitations
            ];
        })
        ->values()      // reindex
        ->toBase();     // convert Eloquent\Collection -> Support\Collection (fixes Livewire dehydrate issue)


        // ✅ Submissions (awarded / not awarded)
        // renamed to $submissionAnnouncements to avoid clobbering later $submissions variable
        $submissionAnnouncements = Submission::with('invitation.ppmp')
            ->where('supplier_id', $supplierId)
            ->latest()
            ->get()
            ->map(function ($sub) {
                $project  = optional($sub->invitation->ppmp)->project_title ?? 'Unknown Project';
                $deadline = optional($sub->invitation)->submission_deadline;
                $status   = strtolower($sub->status ?? '');

                if ($status === 'awarded') {
                    // ✅ Supplier is awarded
                    $message = "🎉 Congratulations! Your submission for <strong>\"{$project}\"</strong> has been <strong>awarded</strong>.";
                    $route   = route('supplier-notice-of-award'); // 👈 awarded route
                } elseif ($deadline && now()->greaterThan($deadline) && $status !== 'awarded') {
                    // ✅ Deadline passed and not awarded
                    $message = "❌ Your submission for <strong>\"{$project}\"</strong> was not selected.";
                    $route   = route('supplier-invitations'); // 👈 fallback route
                } else {
                    // If still pending or deadline not yet passed
                    $message = null;
                    $route   = null;
                }

                return $message ? [
                    'message' => $message,
                    'time'    => $sub->created_at,
                    'route'   => $route,
                ] : null;
            })
            ->filter()     // remove nulls
            ->values()
            ->toBase();    // convert to Support\Collection


        // ✅ Merge both
        $this->announcements = $invitationAnnouncements
            ->concat($submissionAnnouncements)
            ->sortByDesc('time')
            ->values()
            ->toBase();


        // Time bounds
        $now = Carbon::now();
        $yesterday = $now->copy()->subDay(); // last 24 hours
        $twoHoursAgo = $now->copy()->subHours(2); // last 2 hours

        // Submissions within last 24 hours (for this supplier)
        $submissions = Submission::with('invitation.ppmp')
            ->where('supplier_id', $supplierId)
            ->where('created_at', '>=', $yesterday) // ✅ only last 24hrs
            ->latest()
            ->get()
            ->map(function ($sub) {
                $project = optional($sub->invitation->ppmp)->project_title ?? 'Unknown Project';
                return collect([
                    'type' => 'submission',
                    'message' => "You submitted a bid for \"{$project}\"",
                    'time' => $sub->created_at instanceof Carbon ? $sub->created_at : Carbon::parse($sub->created_at),
                ]);
            })
            ->values()
            ->toBase(); // toBase() -> Support\Collection of items (each item can be a Support\Collection)


        // Responses on pivot within last 24 hours (fit on 24hrs only, exclude pending)
        // (cover cases where responded_at may be null but record updated)
        $responses = InvitationSupplier::with('invitation.ppmp')
            ->where('supplier_id', $supplierId)
            ->whereNotNull('response')
            ->where('response', '!=', 'pending')   // 🚀 exclude pending
            ->where(function ($q) use ($yesterday) {
                $q->where('responded_at', '>=', $yesterday) // ✅ must be within 24hrs
                ->orWhere(function ($q2) use ($yesterday) {
                    $q2->whereNull('responded_at')
                        ->where('updated_at', '>=', $yesterday); // ✅ must be within 24hrs
                });
            })
            ->latest('responded_at')
            ->get()
            ->map(function ($resp) {
                $project = optional($resp->invitation->ppmp)->project_title ?? 'Unknown Project';
                $mode = optional($resp->invitation->ppmp)->mode_of_procurement ?? 'unspecified method';
                $time = $resp->responded_at ?: $resp->updated_at;

                // ✅ Human-friendly response wording w/ mode_of_procurement
                $responseText = match (strtolower($resp->response)) {
                    'accepted' => "You accepted the {$mode} invitation for <strong>\"{$project}\"</strong>",
                    'rejected' => "You rejected the {$mode} invitation for <strong>\"{$project}\"</strong>",
                };

                return collect([
                    'type' => 'response',
                    'message' => $responseText,
                    'time' => $time instanceof Carbon ? $time : Carbon::parse($time),
                ]);
            })
            ->values()
            ->toBase();


        // Merge (concat) and sort by time desc, keep Carbon instances for diffForHumans()
        $this->recentActivities = $submissions
            ->concat($responses)
            ->sortByDesc('time')
            ->take(10)
            ->values()
            ->toBase(); // convert to Support\Collection so Livewire won't treat it as Eloquent\Collection


        // ✅ Active procurements for supplier (only accepted invites, not pending)
        $activeProcurements = Ppmp::with([
                'invitations' => function ($q) use ($supplierId) {
                    $q->whereHas('suppliers', function ($sq) use ($supplierId) {
                        $sq->where('users.id', $supplierId)
                        ->where('invitation_supplier.response', 'accepted'); // ✅ only accepted
                    })->with(['submissions' => function ($s) use ($supplierId) {
                        $s->where('supplier_id', $supplierId);
                    }]);
                }
            ])
            ->where('status', 'approved') // ✅ only approved PPMP
            ->whereHas('invitations.suppliers', function ($q) use ($supplierId) {
                $q->where('users.id', $supplierId)
                ->where('invitation_supplier.response', 'accepted'); // ✅ only accepted
            })
            ->whereDoesntHave('invitations.submissions', function ($q) {
                $q->whereRaw('LOWER(submissions.status) = ?', ['awarded']); // ✅ exclude already awarded
            })
            ->latest()
            ->get()
            ->map(function ($ppmp) {
                return [
                    'project' => $ppmp->project_title,
                    'abc'     => $ppmp->abc,
                    'mode'    => $ppmp->mode_of_procurement,
                    'time'    => $ppmp->created_at,
                    // 👇 new: route to supplier proposal submission
                    'route'   => route('supplier-proposal-submission'),
                    // extra info
                    'invitations_count' => $ppmp->invitations->count(),
                ];
            })
            ->values()
            ->toBase(); // convert Eloquent\Collection-of-arrays -> Support\Collection

        // assign to component property so Blade can render it
        $this->activeProcurements = $activeProcurements;
    }

    public function render()
    {
        return view('livewire.supplier-dashboard');
    }
}
