<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ppmp;
use App\Models\AnnouncementsModal;

class PurchaserNotificationBell extends Component
{
    public $ppmpNotifications = [];
    public $ppmpUnreadCount = 0;
    public $notifications = [];

    // Modal
    public $showAnnouncementModal = false;
    public $selectedAnnouncement = null;

    public function mount()
    {
        $this->loadCounts();
    }

    public function loadCounts()
    {
        /* PPMP Notifications */
        $ppmp = Ppmp::with('requester')
            ->latest()
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'type' => 'ppmp',
                    'title' => $p->project_title,
                    'message' => 'Requested by ' . ($p->requester ? $p->requester->first_name . ' ' . $p->requester->last_name : 'Unknown'),
                    'date' => $p->created_at,
                    'is_read' => (bool) $p->is_read,
                ];
            });

        /* Announcement Notifications */
        $announcements = AnnouncementsModal::latest()
            ->get()
            ->map(function ($a) {
                return [
                    'id' => $a->id,
                    'type' => 'announcement',
                    'title' => $a->title,
                    'message' => $a->description,
                    'date' => $a->created_at,
                    'is_read' => (bool) $a->is_read,
                ];
            });

        /* Merge & Sort */
        $all = $ppmp->merge($announcements)->sortByDesc('date')->values();

        $this->notifications = $all->take(20);
        $this->ppmpNotifications = $ppmp->take(20)->toArray();
        $this->ppmpUnreadCount = $ppmp->where('is_read', false)->count() + $announcements->where('is_read', false)->count();
    }

    // Mark single notification as read
    public function markSingleAsRead($type, $id)
    {
        if ($type === 'ppmp') {
            Ppmp::where('id', $id)->update(['is_read' => true]);
        } else {
            AnnouncementsModal::where('id', $id)->update(['is_read' => true]);
            $this->showAnnouncement($id); // open modal for announcement
        }

        $this->loadCounts();
    }

    // Load selected announcement for modal
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
        return view('livewire.purchaser-notification-bell');
    }
}
