<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AwardedItem extends Model
{
    protected $fillable = [
        'ppmp_id',
        'procurement_item_id',
        'invitation_id',
        'supplier_id',
        'sku',
        'description',
        'qty',
        'unit',
        'unit_cost',
        'total_cost',
    ];

    public function ppmp() { 
        return $this->belongsTo(Ppmp::class); 
    }
    public function procurementItem() { 
        return $this->belongsTo(ProcurementItem::class); 
    }
    public function invitation() { 
        return $this->belongsTo(Invitation::class); 
    }
    public function supplier() { 
        return $this->belongsTo(User::class); 
    }
}
