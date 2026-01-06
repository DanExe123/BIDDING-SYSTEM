<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierCategory extends Model
{
    protected $fillable = ['name', 'project_type'];

    public function users()
    {
        return $this->hasMany(User::class, 'supplier_category_id');
    }
}
