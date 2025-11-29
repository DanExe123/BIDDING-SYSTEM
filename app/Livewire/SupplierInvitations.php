<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invitation;
use App\Models\InvitationSupplier;
use Carbon\Carbon;

class SupplierInvitations extends Component
{
    public $invitations = [];
    public $selectedInvitation = null;

    public function mount()
    {
        $userId = auth()->id();

        $this->invitations = Invitation::with(['ppmp', 'suppliers'])
            ->where('status', 'published')
            ->whereHas('suppliers', fn($q) => $q->where('users.id', $userId))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getSupplierResponseAttribute()
    {
        $userId = auth()->id();
        return $this->suppliers
            ->firstWhere('id', $userId)?->pivot?->response ?? 'pending';
    }

    public function selectInvitation($invitationId)
    {
        $this->selectedInvitation = Invitation::with('ppmp', 'suppliers')->find($invitationId);
    }

    public function respondToInvitation($response)
    {
        $userId = auth()->id();

        if ($this->selectedInvitation) {
            InvitationSupplier::updateOrCreate(
                [
                    'invitation_id' => $this->selectedInvitation->id,
                    'supplier_id'   => $userId,
                ],
                [
                    'response'      => $response,
                    'responded_at'  => now(),
                ]
            );

            // Refresh current invitation details
            $this->selectedInvitation = Invitation::with('ppmp', 'suppliers')
                ->find($this->selectedInvitation->id);

            // Flash message
            session()->flash('message', "You have successfully {$response}ed the invitation.");

            $this->closeModal();
            $this->dispatch('close-invitation-modal');
        }
    }

    public function closeModal()
    {
        $this->reset(['selectedInvitation']);
    }


    public function goBack()
    {
        $this->selectedInvitation = null;
    }

    public function render()
    {
        return view('livewire.supplier-invitations');
    }
}
