<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invitation;
use App\Models\InvitationSupplier;
use Carbon\Carbon;
use App\Helpers\LogActivity;

class SupplierInvitations extends Component
{
    public $invitations = [];
    public $selectedInvitation = null;
    public $confirmModal = false;
    public $confirmAction = null; // accepted | declined
    public $remarks = '';


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
        $userId = auth()->id();
        
        $this->selectedInvitation = Invitation::with('ppmp', 'suppliers')->find($invitationId);
        
        // Mark as read
        InvitationSupplier::where('invitation_id', $invitationId)
            ->where('supplier_id', $userId)
            ->update(['is_read' => true]);
        
        // Refresh this row only
        $invitation = $this->invitations->find($invitationId);
        $invitation->load('suppliers');
    }

    /* s
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

            LogActivity::add(
                "{$response} the invitation for Procurement titled '{$this->selectedInvitation->title}' (Ref No. {$this->selectedInvitation->reference_no})"
            );


            $this->closeModal();
            $this->dispatch('close-invitation-modal');
        }
    }*/

    public function submitResponse()
    {
        $userId = auth()->id();

        if ($this->confirmAction === 'declined' && empty(trim($this->remarks))) {
            $this->addError('remarks', 'Remarks are required when declining.');
            return;
        }

        // 🚨 FIRST-COME-FIRST-SERVE LIMIT
        if ($this->confirmAction === 'accepted') {
            $acceptedCount = InvitationSupplier::where('invitation_id', $this->selectedInvitation->id)
                ->where('response', 'accepted')
                ->count();

            if ($acceptedCount >= 3) {
                session()->flash(
                    'message',
                    'This invitation has already been accepted by 3 suppliers.'
                );
                return;
            }
        }

        InvitationSupplier::updateOrCreate(
            [
                'invitation_id' => $this->selectedInvitation->id,
                'supplier_id'   => $userId,
            ],
            [
                'response'     => $this->confirmAction,
                'remarks'      => $this->remarks,
                'responded_at' => now(),
            ]
        );

        LogActivity::add(
            "{$this->confirmAction} the invitation '{$this->selectedInvitation->reference_no}'"
        );

        session()->flash(
            'message',
            "Invitation {$this->confirmAction} successfully."
        );

        $this->reset(['confirmModal', 'confirmAction', 'remarks', 'selectedInvitation']);
        $this->dispatch('close-invitation-modal');
    }

    public function confirmResponse($action)
    {
        $this->confirmAction = $action;
        $this->remarks = '';
        $this->confirmModal = true;
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
