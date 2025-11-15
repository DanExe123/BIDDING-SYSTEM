<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ppmp extends Model
{
    protected $casts = [
        'attachments' => 'array',
        'attachment_names' => 'array',
    ];

    protected $fillable = [
        'project_title',
        'project_type',
        'abc',
        'implementing_unit',
        'description',
        'attachments', 
        'attachment_names',
        'status',
        'requested_by',
        'mode_of_procurement',
    ];

    public function items()
    {
        return $this->hasMany(ProcurementItem::class, 'ppmp_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    // app/Models/Ppmp.php
  //  public function bidInvitations()
   // {
  //      return $this->hasMany(BidInvitation::class, 'ppmp_id', 'id');
  //  }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'ppmp_id', 'id');
    }



}
