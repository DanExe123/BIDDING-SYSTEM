<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ppmp;
use App\Helpers\LogActivity;


class BacProcurementPlanning extends Component
{
    use WithPagination;

    public $selectedPpmp = null;
    public $remarks;

    protected $paginationTheme = 'tailwind'; // optional for Tailwind styles

    public function showPpmp($ppmpId)
    {
        $this->selectedPpmp = Ppmp::with('items', 'requester')->find($ppmpId);
    }

    public function closeModal()
    {
        $this->selectedPpmp = null;
    }

    public function approvePpmp($id)
    {
        $ppmp = Ppmp::findOrFail($id);
        $ppmp->status = 'approved';
        $ppmp->save();

        //activity logs
        LogActivity::add(
        "approved PPMP Request #{$ppmp->id}", 
        );

        $this->closeModal();
        $this->dispatch('close-modal');
        session()->flash('message', 'PPMP approved successfully.');
    }

    public function rejectPpmp($id)
    {
        $this->validate([
            'remarks' => 'required|string|min:3',
        ]);

        $ppmp = Ppmp::findOrFail($id);
        $ppmp->status = 'rejected';
        $ppmp->remarks = $this->remarks;
        $ppmp->save();

        //activitylogs
        LogActivity::add(
            "rejected PPMP Request #{$ppmp->id} | Remarks: {$this->remarks}",
        );

        $this->remarks = null; 
        $this->dispatch('close-reject-modal');
        session()->flash('message', 'PPMP rejected.');
    }

    public function render()
    {
        return view('livewire.bac-procurement-planning', [
            'ppmps' => Ppmp::with('items', 'requester')
                ->where('status', '!=', 'approved')
                ->latest()
                ->paginate(10), // ✅ paginate instead of get()
        ]);
    }
}
