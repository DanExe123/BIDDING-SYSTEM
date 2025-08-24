<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionItem extends Model
{
    protected $fillable = [
        'submission_id',
        'procurement_item_id',
        'unit_price',
        'total_price',
    ];

    public function submission() { 
        return $this->belongsTo(Submission::class); 
    }
    
    public function procurementItem() { 
        return $this->belongsTo(ProcurementItem::class); 
    }
}
