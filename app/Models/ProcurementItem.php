<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementItem extends Model
{
    protected $fillable = [
        'ppmp_id',
        'description',
        'qty',
        'unit',
        'unit_cost',
        'total_cost',
        'delivery_schedule',
    ];

    public function ppmp()
    {
        return $this->belongsTo(Ppmp::class);
    }
}
