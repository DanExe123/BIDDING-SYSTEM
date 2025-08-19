<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BidParticipation extends Model
{
    protected $fillable = [
        'bid_invitation_id',
        'user_id',
        'bid_amount',
        'notes',
        'technical_proposal_path',
        'financial_proposal_path',
        'company_profile_path',
        'is_certified',
        'status',
    ];

    protected $casts = [
        'is_certified' => 'boolean',
        'bid_amount' => 'decimal:2',
    ];

    public function bidInvitation()
    {
        return $this->belongsTo(BidInvitation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
