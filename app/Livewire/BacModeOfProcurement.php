<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ppmp;
use App\Helpers\LogActivity;

class BacModeOfProcurement extends Component
{
    use WithPagination;

    public $selectedPpmp = null;
    public $search = '';
    public $filterMode = '';

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

        LogActivity::add(
            "moved Purchase Request No. {$ppmp->id} to Bidding"
        );

        $this->closeModal();
        $this->dispatch('close-modal');
        session()->flash('message', 'PPMP moved to Bidding successfully.');
    }

    public function movetoQoutation($id)
    {
        $ppmp = Ppmp::findOrFail($id);
        $ppmp->mode_of_procurement = 'quotation';
        $ppmp->save();

        LogActivity::add(
            "moved Purchase Request No. {$ppmp->id} to Quotation"
        );

        $this->closeModal();
        $this->dispatch('close-modal');
        session()->flash('message', 'PPMP moved to Quotation successfully.');
    }

    public function render()
    {
        $ppmps = Ppmp::with('items', 'requester')
            ->where('status', 'approved') // ✅ only approved
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->whereHas('requester', function($rq) {
                        $rq->where('first_name', 'like', '%'.$this->search.'%')
                        ->orWhere('last_name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhere('project_title', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterMode, function($query) {
                if ($this->filterMode === 'not_selected') {
                    $query->whereNull('mode_of_procurement'); // ✅ null
                } else {
                    $query->where('mode_of_procurement', $this->filterMode);
                }
            })
            ->latest()
            ->paginate(10);

        return view('livewire.bac-mode-of-procurement', [
            'ppmps' => $ppmps,
        ]);
    }
}
