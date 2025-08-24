<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invitation;

class BacViewInvitation extends Component
{

    public $ppmp;

    public function mount($ppmp)
    {
        $this->ppmp = $ppmp;
    }

    public $invitation;
    public $suppliers = [];

    public function view($invitationId)
    {
        $this->invitation = Invitation::with(['suppliers.supplierCategory', 'ppmp'])
            ->findOrFail($invitationId);

        $this->suppliers = $this->invitation->suppliers;

        $this->dispatch('open-view-invitation-modal'); // tell Alpine to open modal
    }

    public function render()
    {
        return view('livewire.bac-view-invitation');
    }
}


