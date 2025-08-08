<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ActivityLog;

class SuperadminAudittrails extends Component
{
    public function render()
    {
        $logs = ActivityLog::with('user')->latest()->get();

        return view('livewire.superadmin-audittrails', [
            'logs' => $logs
        ]);
    }
    
}
