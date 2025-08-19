<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BidInvitation;

class BacBidEvaluation extends Component
{
    public $bids = [];
    public $selectedBid = null;

    public function mount()
    {
        // Fetch only published bid invitations
        $this->bids = BidInvitation::with('ppmp')->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function selectBid($bidId)
    {
        // Keep as Eloquent model to preserve relationships
        $this->selectedBid = BidInvitation::with('ppmp', 'suppliers')->find($bidId);
    }

    public function goBack()
    {
        $this->selectedBid = null;
    }

    public function render()
    {
        return view('livewire.bac-bid-evaluation');
    }
}
