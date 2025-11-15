<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementsModal extends Model
{
    use HasFactory;

    protected $table = 'announcements';
    protected $fillable = ['title', 'description', 'date','is_read'];
}
