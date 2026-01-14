<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImplementingUnit extends Model
{
    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->hasMany(User::class, 'implementing_unit_id');
    }

    public function ppmps()
    {
        return $this->hasMany(Ppmp::class, 'implementing_unit_id');
    }
}
