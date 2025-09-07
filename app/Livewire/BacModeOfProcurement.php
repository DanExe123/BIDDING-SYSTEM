<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ppmp;

class BacModeOfProcurement extends Component
{
    use WithPagination;

    public $selectedPpmp = null;

    protected $paginationTheme = 'tailwind'; // Use Tailwind pagination style

    public function showPpmp($ppmpId)
    {
        $this->selectedPpmp = Ppmp::with('items', 'requester')->find($ppmpId);
    }

    public function closeModal()
    {
        $this->selectedPpmp = null;
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
        session()->flash('message', 'PPMP moved to Quotation successfully.');
    }

    public function render()
    {
        return view('livewire.bac-mode-of-procurement', [
            'ppmps' => Ppmp::with('items', 'requester')
                ->where('status', 'approved')
                ->latest()
                ->paginate(10), // ✅ paginate instead of get()
        ]);
    }
}
