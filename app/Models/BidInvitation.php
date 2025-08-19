<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BidInvitation extends Model
{
    protected $fillable = [
        'ppmp_id',
        'bid_title',
        'bid_reference',
        'approved_budget',
        'source_of_funds',
        'pre_bid_date',
        'submission_deadline',
        'bid_documents',
        'invite_scope',
        'specific_suppliers',
        'supplier_category_id',
        'created_by',
        'status',
    ];

    public function ppmp() {
        return $this->belongsTo(Ppmp::class);
    }

    public function supplierCategory() {
        return $this->belongsTo(SupplierCategory::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(User::class, 'bid_invitation_user', 'bid_invitation_id', 'user_id')
                    ->withTimestamps();
    }

    public function participations()
    {
        return $this->hasMany(BidParticipation::class);
    }

    
}
