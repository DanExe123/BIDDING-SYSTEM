<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class AuditTrails extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $viewAll = false;  // Toggle for all logs vs user logs
    public $search = '';      // Search action/location

    public function updatedViewAll() {
        $this->resetPage();  // Reset to page 1 on filter change
    }

    public function updatedSearch() {
        $this->resetPage();
    }

    public function render()
    {
        $query = ActivityLog::query()
            ->when(!$this->viewAll, fn($q) => $q->where('user_id', Auth::id()))
            ->when($this->search, fn($q) => $q->where(function($sub) {
                $sub->where('action', 'like', '%'.$this->search.'%')
                    ->orWhere('location', 'like', '%'.$this->search.'%');
            }))
            ->orderBy('created_at', 'desc');

        $logs = $query->paginate(15);

        return view('livewire.audit-trails', compact('logs'));
    }
}
