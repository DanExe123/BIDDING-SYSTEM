<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InvitationSupplier;
use App\Models\AnnouncementsModal;

class SupplierNotificationBell extends Component
{
    public $notifications;
    public $unreadCount = 0;

    // Modal
    public $showAnnouncementModal = false;
    public $selectedAnnouncement = null;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
{
    $supplierId = auth()->id();

    // Supplier invitations
    $invitations = InvitationSupplier::with('invitation.ppmp')
        ->where('supplier_id', $supplierId)
        ->latest()
        ->take(20)
        ->get()
        ->map(function($item) {
            return [
                'id' => $item->id,
                'type' => $item->response === 'accepted' ? 'accepted' : 'invitation',
                'created_at' => $item->created_at,
                'title' => $item->invitation->ppmp->title ?? 'Invitation',
                'is_read' => $item->is_read,
            ];
        });

    // Announcements
    $announcements = AnnouncementsModal::latest()
        ->take(20)
        ->get()
        ->map(function($a) {
            return [
                'id' => $a->id,
                'type' => 'announcement',
                'created_at' => $a->created_at,
                'title' => $a->title ?? 'Announcement',
                'is_read' => $a->is_read,
                'content' => $a->content ?? '',
            ];
        });

    // Merge as array and sort
    $all = $invitations->concat($announcements)
        ->sortByDesc('created_at')
        ->values();

    // ✅ Convert to plain array for Livewire
    $this->notifications = $all->toArray();

    // Count unread
    $this->unreadCount = collect($this->notifications)
        ->where('is_read', false)
        ->count();
}


    // Mark all as read
    public function markAsRead()
    {
        $supplierId = auth()->id();

        InvitationSupplier::where('supplier_id', $supplierId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        AnnouncementsModal::where('is_read', false)->update(['is_read' => true]);

        $this->loadNotifications();
    }

    // Mark single notification as read
    public function markSingleAsRead($type, $id)
    {
        if ($type === 'announcement') {
            AnnouncementsModal::where('id', $id)->update(['is_read' => true]);
            $this->showAnnouncement($id);
        } else {
            InvitationSupplier::where('id', $id)->update(['is_read' => true]);
        }

        $this->loadNotifications();
    }

    // Show announcement modal
    public function showAnnouncement($id)
    {
        $this->selectedAnnouncement = AnnouncementsModal::find($id);
        $this->showAnnouncementModal = true;
    }

    public function closeModal()
    {
        $this->showAnnouncementModal = false;
        $this->selectedAnnouncement = null;
    }

    public function render()
    {
        return view('livewire.supplier-notification-bell');
    }
}
