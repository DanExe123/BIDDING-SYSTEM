<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'ppmp_id',
        'title',
        'reference_no',
        'approved_budget',
        'source_of_funds',
        'pre_date',
        'submission_deadline',
        'documents',
        'document_names',
        'invite_scope',
        'supplier_category_id',
        'status',
        'created_by',
    ];

    protected $casts = [
        'documents'      => 'array',
        'document_names' => 'array',
        'pre_date'       => 'date',
        'submission_deadline' => 'date',
    ];

    public function ppmp()
    {
        return $this->belongsTo(Ppmp::class);
    }

    public function supplierCategory() {
        return $this->belongsTo(SupplierCategory::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function suppliers()
    {
        return $this->belongsToMany(User::class, 'invitation_supplier', 'invitation_id', 'supplier_id')
                    ->withPivot(['response', 'responded_at', 'is_read', 'remarks'])
                    ->withTimestamps();
    }
    
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

}

