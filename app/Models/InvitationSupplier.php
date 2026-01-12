<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitationSupplier extends Model
{
    protected $table = 'invitation_supplier';

    protected $fillable = [
        'invitation_id',
        'supplier_id',
        'response',
        'responded_at',
        'remarks',
        'is_read',
    ];

    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }
}

