<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InvitationSupplier;

class SupplierNotificationBell extends Component
{
    public $notifications = [];
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $supplierId = auth()->id();

        // ✅ Fetch recent invitation + proposal records
        $records = InvitationSupplier::with('invitation.ppmp')
            ->where('supplier_id', $supplierId)
            ->latest()
            ->take(20)
            ->get();

        // 🔄 Map unified notifications
        $this->notifications = $records->map(function ($item) {
            $isAccepted = $item->response === 'accepted';
            $title = $item->invitation?->ppmp?->project_title ?? 'Untitled Project';

            return [
                'id' => $item->id,
                'type' => $isAccepted ? 'accepted' : 'invitation',
                'title' => $title,
                'reference_no' => $item->invitation?->reference_no ?? null,
                'status' => ucfirst($item->response ?? 'Pending'),
                'created_at' => $item->created_at->diffForHumans(),
                'is_read' => (bool) $item->is_read,
            ];
        })->toArray();

        // 🔔 Count unread
        $this->unreadCount = $records->where('is_read', false)->count();
    }

    public function markAsRead()
    {
        $supplierId = auth()->id();

        InvitationSupplier::where('supplier_id', $supplierId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $this->unreadCount = 0;
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.supplier-notification-bell');
    }
}
