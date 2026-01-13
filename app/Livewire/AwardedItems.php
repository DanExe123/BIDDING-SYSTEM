<?php

namespace App\Livewire;

use App\Models\Ppmp;
use App\Models\AwardedItem;
use Livewire\Component;
use Livewire\WithPagination;

class AwardedItems extends Component
{
    use WithPagination;

    public $showModal = false;
    public $selectedPpmp = null;

    public function showAwardedSuppliers($ppmpId)
    {
        $this->selectedPpmp = Ppmp::with(['items', 'requester'])->find($ppmpId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedPpmp = null;
    }

    public function render()
    {
        $ppmps = Ppmp::with(['requester', 'invitations.submissions' => function($query) {
                return $query->where('status', 'awarded')->with('supplier');
            }])
            ->whereHas('invitations.submissions', function($query) {
                $query->where('status', 'awarded');
            })
            ->paginate(10);

        return view('livewire.awarded-items', [
            'ppmps' => $ppmps
        ]);
    }
}
