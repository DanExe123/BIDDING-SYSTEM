<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ppmp;

class BacNoticeOfAward extends Component
{
    use WithPagination;

    public $selectedPpmp;

    public function showAwardedSuppliers($ppmpId)
    {
        $this->selectedPpmp = Ppmp::with([
            'invitations.submissions.supplier'
        ])->findOrFail($ppmpId);
    }

    public function render()
    {
        // Fetch only PPMPs with awarded submissions
        $ppmps = Ppmp::with(['invitations.submissions.supplier'])
            ->whereHas('invitations.submissions', function ($q) {
                $q->where('status', 'awarded');
            })
            ->paginate(10);

        return view('livewire.bac-notice-of-award', [
            'ppmps' => $ppmps,
        ]);
    }
}
