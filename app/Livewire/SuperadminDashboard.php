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

        //  Only verified suppliers
        $verifiedSuppliers = User::whereHas('roles', fn($q) => 
            $q->where('name', 'Supplier')
        )->where('account_status', 'verified')->get();

        //  Pending suppliers (not yet verified)
        $pendingSuppliers = User::whereHas('roles', fn($q) => 
            $q->where('name', 'Supplier')
        )->where('account_status', '!=', 'verified')->get();

        $this->adminCount = $admins->count();
        $this->purchaserCount = $purchasers->count();
        $this->verifiedSupplierCount = $verifiedSuppliers->count();
        $this->pendingSupplierCount = $pendingSuppliers->count();

        // 🔹 Optional notifications
        $this->notifications = collect()
            ->merge($admins->map(fn($u) => [
                'name' => "{$u->first_name} {$u->last_name}",
                'role' => 'BAC Secretary',
                'icon' => 'users',
                'color' => 'text-blue-500'
            ]))
            ->merge($purchasers->map(fn($u) => [
                'name' => "{$u->first_name} {$u->last_name}",
                'role' => 'Purchaser',
                'icon' => 'shopping-cart',
                'color' => 'text-green-500'
            ]))
            ->merge($verifiedSuppliers->map(fn($u) => [
                'name' => "{$u->first_name} {$u->last_name}",
                'role' => 'Verified Supplier',
                'icon' => 'factory',
                'color' => 'text-yellow-500'
            ]))
            ->toArray();

        $this->unreadCount = User::where('is_read', false)->count();
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
