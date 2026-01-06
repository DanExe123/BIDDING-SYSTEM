<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ppmp;

class BacNoticeOfAward extends Component
{
    use WithPagination;

    public $selectedPpmp;
    public $search = '';
    public $modeFilter = '';
    public $isPurchaser = false; 



    public function showAwardedSuppliers($ppmpId)
    {
        $this->selectedPpmp = Ppmp::with([
            'invitations.submissions.supplier'
        ])->findOrFail($ppmpId);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedModeFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Ppmp::with(['invitations.submissions.supplier'])
            ->whereHas('invitations.submissions', function ($q) {
                $q->where('status', 'awarded');
            });

        // ✅ ROLE-BASED FILTERING
        $this->isPurchaser = auth()->user()->hasRole('Purchaser');
        if ($this->isPurchaser) {
            $query->where('requested_by', auth()->id());
        }

        // 🔍 SEARCH (works for both roles)
        $query->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('project_title', 'like', '%' . $this->search . '%')
                ->orWhereHas('invitations', function ($inv) {
                    $inv->where('reference_no', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('invitations.submissions.supplier', function ($sup) {
                    $sup->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            });
        });

        // 🟦 MODE OF PROCUREMENT FILTER (works for both roles)
        $query->when($this->modeFilter, function ($query) {
            $query->where('mode_of_procurement', $this->modeFilter);
        });

        return view('livewire.bac-notice-of-award', [
            'ppmps' => $query->orderBy('created_at', 'desc')->paginate(10),
            'isPurchaser' => $this->isPurchaser,
        ]);
    }

}
