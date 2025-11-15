<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\AnnouncementsModal;

class SuperadminDashboard extends Component
{
    public $adminCount;
    public $purchaserCount;
    public $supplierCount;
    public $unreadCount = 0; 
    public $notifications = [];
    public $announcements = []; 

    public function mount()
    {
        $this->loadCounts();
        $this->loadAnnouncements();
    }

    public function loadCounts()
    {
        $admins = User::whereHas('roles', fn($q) => 
            $q->where('name', 'BAC_Secretary')
        )->get();

        $purchasers = User::whereHas('roles', fn($q) => 
            $q->where('name', 'Purchaser')
        )->get();

        $suppliers = User::whereHas('roles', fn($q) => 
            $q->where('name', 'Supplier')
        )->get();

        $this->adminCount = $admins->count();
        $this->purchaserCount = $purchasers->count();
        $this->supplierCount = $suppliers->count();

        // 🔹 Build notification list (users)
        $userNotifications = $admins->map(fn($u) => [
            'id' => $u->id,
            'name' => $u->first_name . ' ' . $u->last_name,
            'role' => 'BAC Secretary',
            'icon' => 'users',
            'color' => 'text-blue-500',
            'type' => 'user',
            'is_read' => (bool) $u->is_read,
        ])->merge(
            $purchasers->map(fn($u) => [
                'id' => $u->id,
                'name' => $u->first_name . ' ' . $u->last_name,
                'role' => 'Purchaser',
                'icon' => 'shopping-cart',
                'color' => 'text-green-500',
                'type' => 'user',
                'is_read' => (bool) $u->is_read,
            ])
        )->merge(
            $suppliers->map(fn($u) => [
                'id' => $u->id,
                'name' => $u->first_name . ' ' . $u->last_name,
                'role' => 'Supplier',
                'icon' => 'factory',
                'color' => 'text-purple-500',
                'type' => 'user',
                'is_read' => (bool) $u->is_read,
            ])
        );

        // 🔹 Announcement notifications
        $announcementNotifications = AnnouncementsModal::orderBy('date', 'desc')->get()->map(fn($a) => [
            'id' => $a->id,
            'title' => $a->title,
            'message' => $a->description,
            'date' => $a->date,
            'type' => 'announcement',
            'is_read' => (bool) $a->is_read,
        ]);

        // 🔹 Merge users + announcements & sort by date descending
        $this->notifications = $userNotifications->merge($announcementNotifications)
            ->sortByDesc(fn($n) => $n['date'] ?? now())
            ->values()
            ->toArray();

        // 🔹 Count unread for badge
        $this->unreadCount = collect($this->notifications)->where('is_read', false)->count();
    }

    public function markAsRead()
    {
        // Mark all users and announcements as read
        User::where('is_read', false)->update(['is_read' => true]);
        AnnouncementsModal::where('is_read', false)->update(['is_read' => true]);

        $this->unreadCount = 0;
        $this->loadCounts();
    }

    public function loadAnnouncements()
    {
        $this->announcements = AnnouncementsModal::orderBy('date', 'desc')->get();
    }

    // Optional: mark single notification as read
    public function markSingleAsRead($type, $id)
    {
        if ($type === 'user') {
            User::where('id', $id)->update(['is_read' => true]);
        } elseif ($type === 'announcement') {
            AnnouncementsModal::where('id', $id)->update(['is_read' => true]);
        }

        $this->loadCounts();
    }

    public function render()
    {
        return view('livewire.superadmin-dashboard');
    }
}
