<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'email',
        'password',
        'supplier_category_id',
        'implementing_unit_id', 
        'business_permit',
        'account_status',
        'bpl_file_name',
        'remarks',
        'contact_no',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function supplierCategory()
    {
        return $this->belongsTo(SupplierCategory::class, 'supplier_category_id');
    }

    public function implementingUnit()
    {
        return $this->belongsTo(ImplementingUnit::class, 'implementing_unit_id');
    }

    public function bidInvitations()
    {
        return $this->belongsToMany(BidInvitation::class, 'bid_invitation_user', 'user_id', 'bid_invitation_id')
                    ->withTimestamps();
    }
   
    public function bidParticipations()
    {
        return $this->hasMany(\App\Models\BidParticipation::class);
    }

}
