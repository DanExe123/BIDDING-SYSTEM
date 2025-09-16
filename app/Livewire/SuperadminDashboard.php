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

    public function mount()
    {
        $this->loadCounts();
    }

    public function loadCounts()
    {
        $this->adminCount = User::whereHas('roles', fn($q) => 
            $q->where('name', 'BAC_Secretary')
        )->count();

        $this->purchaserCount = User::whereHas('roles', fn($q) => 
            $q->where('name', 'Purchaser')
        )->count();

        $this->supplierCount = User::whereHas('roles', fn($q) => 
            $q->where('name', 'Supplier')
        )->count();

        // set unread count = total
        $this->unreadCount = $this->adminCount + $this->purchaserCount + $this->supplierCount;
    }

    // 🔹 Function to mark all notifications as read
    public function markAsRead()
    {
        $this->unreadCount = 0;
    }

    public function render()   
    {
        return view('livewire.superadmin-dashboard');
    }
}
