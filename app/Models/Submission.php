<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'invitation_id',
        'supplier_id',
        'bid_amount',
        'technical_proposal_path',
        'financial_proposal_path',
        'company_profile_path',
        'is_certified',
        'remarks',
        'status',
        'submitted_at',
        'technical_score',
        'financial_score',
        'delivery_days',
        'total_score',
        'award_date',
        'technical_proposal_original_name',
        'financial_proposal_original_name',
        'company_profile_original_name',
    ];

    public function invitation() { 
        return $this->belongsTo(Invitation::class); 
    }
    public function supplier() { 
        return $this->belongsTo(User::class, 'supplier_id'); 
    }
    public function items() { 
        return $this->hasMany(SubmissionItem::class); 
    }
}
