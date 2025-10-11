<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ppmp;
use App\Models\Submission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PurchaserDashboard extends Component
{
    public $approvedOrRejectedPpmps;
    public $recentlyAwardedPpmps;
    public $recentPurchaserActivities;

    public function mount()
    {
        $userId = Auth::id();

        // Fetch PPMPs approved or rejected by BAC Sec
         $this->approvedOrRejectedPpmps = Ppmp::where('requested_by', $userId)
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // ✅ Fetch awarded PPMPs (belonging to this purchaser) within the last 24 hours
        $this->recentlyAwardedPpmps = Submission::whereNotNull('award_date')
            ->whereHas('invitation.ppmp', function ($query) use ($userId) {
                $query->where('requested_by', $userId);
            })
            ->with('invitation.ppmp')
            ->orderBy('award_date', 'desc')
            ->get();

        // ✅ Fetch PPMPs created by this purchaser in the last 24 hours
        $this->recentPurchaserActivities = Ppmp::where('requested_by', $userId)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.purchaser-dashboard');
    }
}
