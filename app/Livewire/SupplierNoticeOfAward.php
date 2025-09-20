<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ppmp;
use Illuminate\Support\Facades\Auth;

class SupplierNoticeOfAward extends Component
{
    use WithPagination;

    public $selectedPpmp;

    public function showAwardedSuppliers($ppmpId)
    {
        $this->selectedPpmp = Ppmp::with([
            'invitations.submissions' => function ($q) {
                $q->where('status', 'awarded')
                  ->where('supplier_id', Auth::id()); // only this supplier
            }
        ])->findOrFail($ppmpId);
    }

    public function render()
    {
        $supplierId = Auth::id();

        // Fetch only PPMPs awarded to this supplier
        $ppmps = Ppmp::with(['invitations.submissions' => function ($q) use ($supplierId) {
                $q->where('status', 'awarded')
                  ->where('supplier_id', $supplierId);
            }])
            ->whereHas('invitations.submissions', function ($q) use ($supplierId) {
                $q->where('status', 'awarded')
                  ->where('supplier_id', $supplierId);
            })
            ->paginate(10);

        return view('livewire.supplier-notice-of-award', [
            'ppmps' => $ppmps,
        ]);
    }
}
