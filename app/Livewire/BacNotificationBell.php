<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ppmp;

class BacNotificationBell extends Component
{
    public $ppmpUnreadCount = 0;
    public $ppmpNotifications = [];

    // Poll every few seconds
    public function loadCounts()
    {
        $this->ppmpUnreadCount = Ppmp::where('is_read', false)->count();

        $this->ppmpNotifications = Ppmp::with('requester')
            ->latest()
            ->take(10) // limit results
            ->get()
            ->map(function ($ppmp) {
                return [
                    'id'           => $ppmp->id,
                    'title'        => $ppmp->project_title,
                    'requested_by' => $ppmp->requester->name ?? 'N/A',
                    'is_read'      => $ppmp->is_read,
                ];
            })
            ->toArray();
    }

    // When bell is opened, mark unread as read
    public function markAsRead()
    {
        Ppmp::where('is_read', false)->update(['is_read' => true]);
        $this->loadCounts();
    }

    public function mount()
    {
        $this->loadCounts();
    }

    public function render()
    {
        return view('livewire.bac-notification-bell');
    }
}
