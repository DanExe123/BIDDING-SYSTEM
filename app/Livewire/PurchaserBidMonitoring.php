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
        $this->ppmps = Ppmp::where('requested_by', Auth::id()) // Fetch only their own PPMPs
            ->latest()
            ->get();
    }
    public function render()
    {
        return view('livewire.purchaser-bid-monitoring');
    }
}
