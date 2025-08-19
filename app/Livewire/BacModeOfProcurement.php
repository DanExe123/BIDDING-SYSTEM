<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ppmp;

class BacModeOfProcurement extends Component
{
    public $ppmps = [];
    public $selectedPpmp = null;

    public function showPpmp($ppmpId)
    {
        $this->selectedPpmp = Ppmp::with('items', 'requester')->find($ppmpId);
    }

    public function closeModal()
    {
        $this->selectedPpmp = null;
    }

    public function mount()
    {
        // eager-load items but exclude approved ppmps
        $this->ppmps = Ppmp::with('items')
            ->where('status', 'approved')
            ->latest()
            ->get();
    }


    public function movetoBidding($id)
    {
        $ppmp = Ppmp::findOrFail($id);
        $ppmp->mode_of_procurement = 'bidding';
        $ppmp->save();

        $this->closeModal();
        $this->dispatch('close-modal');
        session()->flash('message', 'PPMP moved to Bidding successfully.');
    }

    public function movetoQoutation($id)
    {
        $ppmp = Ppmp::findOrFail($id);
        $ppmp->mode_of_procurement = 'quotation';
        $ppmp->save();

        $this->closeModal();
        $this->dispatch('close-modal');
        session()->flash('message', 'PPMP moved to Qoutation successfully.');
    }

    public function render()
    {
        return view('livewire.bac-mode-of-procurement');
    }
}
