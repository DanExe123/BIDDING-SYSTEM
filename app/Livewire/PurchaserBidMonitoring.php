<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ppmp;
use Illuminate\Support\Facades\Auth;

class PurchaserBidMonitoring extends Component
{
    public $ppmps = [];

    public function mount()
    {
        $this->ppmps = Ppmp::with('items') // load the related items
            ->where('requested_by', Auth::id())
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.purchaser-bid-monitoring');
    }
}
