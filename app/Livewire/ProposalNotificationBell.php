<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InvitationSupplier;
use Illuminate\Support\Facades\Auth;

class ProposalNotificationBell extends Component
{
    public $proposalNotifications = [];
    public $proposalUnreadCount = 0;

    public function mount()
    {
        $this->loadCounts();
    }

    /**
     * 🔹 Load notifications grouped by invitation_id
     */
    public function loadCounts()
    {
        $supplierId = Auth::id();

        // Get unique invitations for this supplier
        $invitations = InvitationSupplier::with('invitation.ppmp')
            ->where('supplier_id', $supplierId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('invitation_id'); // ✅ Group by invitation_id

        $notifications = [];

        foreach ($invitations as $invitationId => $records) {
            $latest = $records->sortByDesc('created_at')->first(); // latest status per invitation

            $notifications[] = [
                'invitation_id' => $invitationId,
                'title' => $latest->invitation?->ppmp?->project_title ?? 'New Proposal Update',
                'status' => ucfirst($latest->response ?? 'Pending'),
                'status_color' => match ($latest->response) {
                    'accepted' => 'text-green-500',
                    'declined' => 'text-red-500',
                    'pending' => 'text-yellow-500',
                    default => 'text-gray-500',
                },
                'date' => $latest->created_at?->diffForHumans(),
                'is_read' => (bool) $latest->is_read,
            ];
        }

        // Sort by latest date
        usort($notifications, fn($a, $b) => strtotime($b['date']) <=> strtotime($a['date']));

        $this->proposalNotifications = array_slice($notifications, 0, 10); // limit 10
        $this->proposalUnreadCount = collect($notifications)->where('is_read', false)->count();
    }

    /**
     * 🔹 Mark all proposal notifications as read
     */
    public function markAsRead()
    {
        InvitationSupplier::where('supplier_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $this->proposalUnreadCount = 0;
        $this->loadCounts();
    }

    public function render()
    {
        return view('livewire.proposal-notification-bell');
    }
}
