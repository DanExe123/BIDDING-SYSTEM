<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BidInvitation;

class SupplierBidInitiation extends Component
{
    public $bids = [];
    public $selectedBid = null;

    public function mount()
    {
        $userId = auth()->id();

        $this->bids = BidInvitation::with('ppmp', 'suppliers', 'supplierCategory')
            ->where('status', 'published')
            ->where(function($query) use ($userId) {
                $query->where('invite_scope', 'all')
                     ->orWhere(function($q) use ($userId) {
                        $q->where('invite_scope', 'specific')
                        ->whereHas('suppliers', fn($q2) => $q2->where('users.id', $userId));
                    })
                    ->orWhere(function($q) use ($userId) {
                        $q->where('invite_scope', 'category')
                        ->whereHas('supplierCategory.users', fn($q2) => $q2->where('users.id', $userId));
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function selectBid($bidId)
    {
        $this->selectedBid = BidInvitation::with('ppmp', 'suppliers')->find($bidId);
    }

    public function goBack()
    {
        $this->selectedBid = null;
    }

    public function render()
    {
        return view('livewire.supplier-bid-initiation');
    }
}
