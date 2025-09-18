<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class SuperadminDashboard extends Component
{
    public $adminCount;
    public $purchaserCount;
    public $supplierCount;
    public $unreadCount; 
    public $notifications = [];

    public function mount()
    {
        $this->loadCounts();
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

        // 🔹 Build notification list (all users individually)
        $this->notifications = $admins->map(fn($u) => [
            'name' => $u->first_name . ' ' . $u->last_name,
            'role' => 'BAC Secretary',
            'icon' => 'users',
            'color' => 'text-blue-500'
        ])->merge(
            $purchasers->map(fn($u) => [
                'name' => $u->first_name . ' ' . $u->last_name,
                'role' => 'Purchaser',
                'icon' => 'shopping-cart',
                'color' => 'text-green-500'
            ])
        )->merge(
            $suppliers->map(fn($u) => [
                'name' => $u->first_name . ' ' . $u->last_name,
                'role' => 'Supplier',
                'icon' => 'factory',
                'color' => 'text-purple-500'
            ])
        )->toArray();

        $this->unreadCount = count($this->notifications);
    }

    public function markAsRead()
    {
        $this->unreadCount = 0;
    }

    public function render()
    {
        return view('livewire.superadmin-dashboard');
    }
}
