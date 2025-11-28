<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\AnnouncementsModal;

class SuperadminDashboard extends Component
{
    public $adminCount;
    public $purchaserCount;
    public $verifiedSupplierCount;
    public $pendingSupplierCount;
    public $unreadCount; 
    public $notifications = [];
    public $announcements = []; 
    public $editId = null;
    public $deleteId = null;
    public $title = '';
    public $description = '';
    public $date = '';
    public $showEditModal = false;
    public $toast = null;

    protected $listeners = ['deleteAnnouncement'];


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

        $verifiedSuppliers = User::whereHas('roles', fn($q) => 
            $q->where('name', 'Supplier')
        )->where('account_status', 'verified')->get();

        $pendingSuppliers = User::whereHas('roles', fn($q) => 
            $q->where('name', 'Supplier')
        )->where('account_status', '!=', 'verified')->get();

        $this->adminCount = $admins->count();
        $this->purchaserCount = $purchasers->count();
        $this->verifiedSupplierCount = $verifiedSuppliers->count();
        $this->pendingSupplierCount = $pendingSuppliers->count();

        // 🔹 USER NOTIFICATIONS (Fixed: added id, type, is_read, date)
        $userNotifications = collect()
            ->merge($admins->map(fn($u) => [
                'id' => $u->id,
                'type' => 'user',
                'is_read' => (bool) $u->is_read,
                'date' => $u->updated_at,

                'name' => "{$u->first_name} {$u->last_name}",
                'role' => 'BAC Secretary',
                'icon' => 'users',
                'color' => 'text-blue-500'
            ]))
            ->merge($purchasers->map(fn($u) => [
                'id' => $u->id,
                'type' => 'user',
                'is_read' => (bool) $u->is_read,
                'date' => $u->updated_at,

                'name' => "{$u->first_name} {$u->last_name}",
                'role' => 'Purchaser',
                'icon' => 'shopping-cart',
                'color' => 'text-green-500'
            ]))
            ->merge($verifiedSuppliers->map(fn($u) => [
                'id' => $u->id,
                'type' => 'user',
                'is_read' => (bool) $u->is_read,
                'date' => $u->updated_at,

                'name' => "{$u->first_name} {$u->last_name}",
                'role' => 'Verified Supplier',
                'icon' => 'factory',
                'color' => 'text-yellow-500'
            ]));

        // 🔹 ANNOUNCEMENT NOTIFICATIONS (Fixed: added type, is_read)
        $announcementNotifications = AnnouncementsModal::orderBy('date', 'desc')
            ->get()
            ->map(fn($a) => [
                'id' => $a->id,
                'type' => 'announcement',
                'is_read' => (bool) $a->is_read,
                'date' => $a->date,

                'title' => $a->title,
                'message' => $a->description
            ]);

        // 🔹 MERGE & SORT
        $this->notifications = $userNotifications
            ->merge($announcementNotifications)
            ->sortByDesc(fn($n) => $n['date'])
            ->values()
            ->toArray();

        // 🔹 Count unread items
        $this->unreadCount = collect($this->notifications)
            ->where('is_read', false)
            ->count();
    }

    public function markAsRead()
    {
        User::where('is_read', false)->update(['is_read' => true]);
        AnnouncementsModal::where('is_read', false)->update(['is_read' => true]);

        $this->unreadCount = 0;
        $this->loadCounts();
    }

    public function loadAnnouncements()
    {
        $this->announcements = AnnouncementsModal::orderBy('date', 'desc')->get();
    }

    public function markSingleAsRead($type, $id)
    {
        if ($type === 'user') {
            User::where('id', $id)->update(['is_read' => true]);
        } elseif ($type === 'announcement') {
            AnnouncementsModal::where('id', $id)->update(['is_read' => true]);
        }

        $this->loadCounts();
    }

    public function editAnnouncement($id)
    {
        $announcement = AnnouncementsModal::findOrFail($id);

        $this->editId = $announcement->id;
        $this->title = $announcement->title;
        $this->description = $announcement->description;
        $this->date = $announcement->date;

        $this->showEditModal = true;
    }

    public function updateAnnouncement()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date'
        ]);

        AnnouncementsModal::find($this->editId)->update([
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
        ]);

        $this->showEditModal = false;
        $this->loadAnnouncements();

        session()->flash('message', 'Announcement updated successfully!');
    }
        // -----------------------------------------------------
        public function deleteAnnouncement($id)
        {
            $announcement = AnnouncementsModal::find($id);
    
            if ($announcement) {
                $announcement->delete();
                $this->loadAnnouncements(); // refresh table
    
                $this->toast = [
                    'type' => 'success',
                    'message' => 'Announcement deleted successfully!'
                ];
            } else {
                $this->toast = [
                    'type' => 'error',
                    'message' => 'Announcement not found!'
                ];
                return redirect()->route('superadmin-dashboard');
            }
        }

        

    public function render()
    {
        return view('livewire.superadmin-dashboard');
    }
}
