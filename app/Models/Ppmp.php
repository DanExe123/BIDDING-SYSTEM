<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ppmp extends Model
{
    protected $fillable = [
        'project_title',
        'project_type',
        'abc',
        'implementing_unit',
        'description',
        'attachment',
        'status',
        'requested_by',
        'mode_of_procurement',
    ];

    public function items()
    {
        return $this->hasMany(ProcurementItem::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    // app/Models/Ppmp.php
    public function bidInvitations()
    {
        return $this->hasMany(BidInvitation::class, 'ppmp_id', 'id');
    }



}
