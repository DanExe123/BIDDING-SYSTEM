<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ppmp;

class PurchaserNotificationBell extends Component
{
    public $ppmpNotifications = [];
    public $ppmpUnreadCount = 0;

    public function mount()
    {
        $this->loadCounts();
    }

    public function loadCounts()
    {
        // Get recent PPMPs (all) for display
        $ppmps = Ppmp::with('requester')
            ->latest()
            ->take(10) // optionally limit to recent 10
            ->get();

        // Map for dropdown
        $this->ppmpNotifications = $ppmps->map(fn($p) => [
            'title' => $p->project_title,
            'requested_by' => $p->requester ? $p->requester->first_name . ' ' . $p->requester->last_name : 'Unknown',
            'is_read' => (bool) $p->is_read,
        ])->toArray();

        // Count unread for badge
        $this->ppmpUnreadCount = $ppmps->where('is_read', false)->count();
    }

    public function markAsRead()
    {
        // Mark all as read
        Ppmp::where('is_read', false)->update(['is_read' => true]);

        // Update unread count
        $this->ppmpUnreadCount = 0;

        // Reload notifications (keep them visible)
        $this->loadCounts();
    }

    public function render()
    {
        return view('livewire.purchaser-notification-bell');
    }
}
