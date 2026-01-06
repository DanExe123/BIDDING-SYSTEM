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
    public $search = '';
    public $filterStatus = '';


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
        $ppmps = Ppmp::with('items', 'requester')
            // ✅ Always include only pending and rejected PPMPs
            //->whereIn('status', ['pending', 'approved', 'rejected'])
            
            // ✅ Apply search only to pending/rejected PPMPs
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->whereHas('requester', function($rq) {
                        // Search by requester first_name or last_name
                        $rq->where('first_name', 'like', '%'.$this->search.'%')
                        ->orWhere('last_name', 'like', '%'.$this->search.'%');
                    })
                    // Search by project_title (still only pending/rejected)
                    ->orWhere('project_title', 'like', '%'.$this->search.'%');
                });
            })
            
            // ✅ Apply status filter only to pending/rejected (ignores any attempt to filter by approved/awarded)
            ->when($this->filterStatus, function($query) {
                if (in_array($this->filterStatus, ['pending', 'rejected'])) {
                    $query->where('status', $this->filterStatus);
                }
            })
            
            ->latest()
            ->paginate(10);

        return view('livewire.bac-procurement-planning', [
            'ppmps' => $ppmps,
        ]);
    }
}
