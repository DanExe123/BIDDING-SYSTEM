<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class SuperadminDashboard extends Component
{
    public $adminCount;
    public $purchaserCount;
    public $supplierCount;

    public function mount()
    {
        $this->adminCount = User::whereHas('roles', function($q) {
            $q->where('name', 'BAC_Secretary');
        })->count();

        $this->purchaserCount = User::whereHas('roles', function($q) {
            $q->where('name', 'Purchaser');
        })->count();

        $this->supplierCount = User::whereHas('roles', function($q) {
            $q->where('name', 'Supplier');
        })->count();
    }

    public function render()
    {
        return view('livewire.superadmin-dashboard');
    }
}
