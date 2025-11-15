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

        // 🔹 Supplier invitations
        $invitations = InvitationSupplier::with('invitation.ppmp')
            ->where('supplier_id', $supplierId)
            ->latest()
            ->take(20)
            ->get();

        $invitations->each(function($item){
            $item->type = $item->response === 'accepted' ? 'accepted' : 'invitation';
        });

        // 🔹 Announcements
        $announcements = AnnouncementsModal::latest()
            ->take(20)
            ->get()
            ->map(function ($a) {
                $a->type = 'announcement';
                return $a;
            });

        // 🔹 Merge all
        $all = $invitations->merge($announcements)->sortByDesc('created_at');

        $this->notifications = $all;

        // 🔹 Count unread
        $this->unreadCount = $all->where('is_read', false)->count();
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
