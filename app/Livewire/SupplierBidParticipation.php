<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\BidInvitation;
use App\Models\BidParticipation;
use Illuminate\Support\Facades\Auth;

class SupplierBidParticipation extends Component
{
    use WithFileUploads;

    public $bids = [];
    public $selectedBid = null;      // BidInvitation
    public $showModal = false;

    // form fields
    public $bid_amount;
    public $notes;
    public $technical_proposal;      // file
    public $financial_proposal;      // file
    public $company_profile;         // file
    public $is_certified = false;

    protected $rules = [
        'bid_amount'         => 'required|numeric|min:1',
        'notes'              => 'nullable|string|max:2000',
        'technical_proposal' => 'nullable|file|mimes:pdf|max:10240', // 10MB
        'financial_proposal' => 'nullable|file|mimes:xlsx,xls,csv|max:5120', // 5MB
        'company_profile'    => 'nullable|file|mimes:pdf|max:5120', // 5MB
        'is_certified'       => 'accepted',
    ];

    public function mount()
    {
        $uid = Auth::id();

        // Only published, not past deadline, and invited (or open to all)
        $this->bids = BidInvitation::with('ppmp')
            ->where('status', 'published')
            ->whereDate('submission_deadline', '>=', now())
            ->where(function ($q) use ($uid) {
                $q->where('invite_scope', 'all')
                  ->orWhereHas('suppliers', fn($s) => $s->where('users.id', $uid));
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function selectBid($id)
    {
        $this->resetForm();
        $this->selectedBid = BidInvitation::with('ppmp')->findOrFail($id);
        $this->showModal = true;
    }

    public function submitProposal()
    {
        $this->validate();

        // Optional: block if > ABC
        if ($this->selectedBid && $this->bid_amount > (float) $this->selectedBid->approved_budget) {
            $this->addError('bid_amount', 'Your bid cannot exceed the ABC.');
            return;
        }

        $uid   = Auth::id();
        $bidId = $this->selectedBid->id;
        $base  = "bids/{$bidId}/supplier-{$uid}";

        $paths = [
            'technical_proposal_path' => $this->technical_proposal?->store("{$base}", 'public'),
            'financial_proposal_path' => $this->financial_proposal?->store("{$base}", 'public'),
            'company_profile_path'    => $this->company_profile?->store("{$base}", 'public'),
        ];

        // Create or update (unique constraint enforces one)
        BidParticipation::updateOrCreate(
            ['bid_invitation_id' => $bidId, 'user_id' => $uid],
            [
                'bid_amount'   => $this->bid_amount,
                'notes'        => $this->notes,
                'is_certified' => (bool) $this->is_certified,
                'status'       => 'submitted',
            ] + array_filter($paths) // only save uploaded ones
        );

        session()->flash('message', 'Bid submitted successfully.');
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'bid_amount','notes','technical_proposal','financial_proposal','company_profile','is_certified'
        ]);
        $this->is_certified = false;
    }

    public function render()
    {
        return view('livewire.supplier-bid-participation', [
            'bids' => $this->bids,
        ]);
    }
}
