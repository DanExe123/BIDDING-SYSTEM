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
     public $search = '';
    public $statusFilter = ''; // 'received', 'not_received', 'all'

    public function markReceived($itemId)
    {
        AwardedItem::where('id', $itemId)->update(['status' => 'received']);
        session()->flash('message', 'Item marked as received!');
    }

    public function viewItem($itemId)
    {
        $item = AwardedItem::with(['ppmp', 'supplier', 'procurementItem'])->find($itemId);
        session()->flash('message', 'View item: ' . $item->sku);
    }

    public function removeItem($itemId)
    {
        AwardedItem::where('id', $itemId)->delete();
        session()->flash('message', 'Item removed successfully!');
    }


    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $awardedItems = AwardedItem::with([
            'supplier',
            'ppmp.requester.implementingUnit',
            'ppmp.invitations',
            'invitation'
        ])
        ->when($this->search, function($query) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('sku', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm)
                  ->orWhereHas('ppmp.requester', function($sub) use ($searchTerm) {
                      $sub->whereRaw('CONCAT(first_name, " ", last_name) LIKE ?', [$searchTerm])
                          ->orWhereHas('implementingUnit', fn($sub2) => $sub2->where('name', 'like', $searchTerm));
                  })
                  ->orWhereHas('supplier', fn($sub) => $sub->where('first_name', 'like', $searchTerm))
                  ->orWhereHas('ppmp.invitations', fn($sub) => $sub->where('reference_no', 'like', $searchTerm));
            });
        })
        ->when($this->statusFilter === 'received', fn($query) => $query->where('status', 'received'))
        ->when($this->statusFilter === 'not_received', fn($query) => $query->where('status', 'not_received'))
        ->latest()
        ->paginate(10);

        return view('livewire.awarded-items', [
            'awardedItems' => $awardedItems
        ]);
    }

}
