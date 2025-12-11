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

    public function render()
    {
        // Fetch only the current user's activity logs
        $logs = ActivityLog::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.audit-trails', compact('logs'));
    }
}
