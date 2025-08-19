<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ppmp;

class BacProcurementPlanning extends Component
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
            ->where('status', '!=', 'approved')
            ->latest()
            ->get();
    }


    public function approvePpmp($id)
    {
        $ppmp = Ppmp::findOrFail($id);
        $ppmp->status = 'approved';
        $ppmp->save();

        $this->closeModal();
        $this->dispatch('close-modal');
        session()->flash('message', 'PPMP approved successfully.');
    }

    public function rejectPpmp($id)
    {
        $ppmp = Ppmp::findOrFail($id);
        $ppmp->status = 'rejected';
        $ppmp->save();

        $this->closeModal();
        $this->dispatch('close-modal');
        session()->flash('message', 'PPMP rejected successfully.');
    }


    public function render()
    {
        return view('livewire.bac-procurement-planning');
    }
}
