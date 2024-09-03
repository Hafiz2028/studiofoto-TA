<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Owner extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'ktp',
        'logo',
        'email_verified_at',
        'verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getPictureAttribute($value)
    {
        return $value ? asset('/images/users/owners/' . $value) : asset('/images/users/default-avatar.png');
    }
    public function getKtpAttribute($value)
    {
        return $value ? asset('/images/users/owners/KTP_owner/' . $value) : asset('/images/users/owners/KTP_owner/ktp.png');
    }

    public function getLogoAttribute($value)
    {
        return $value ? asset('/images/users/owners/LOGO_owner/' . $value) : asset('/images/users/owners/LOGO_owner/default-logo.png');
    }

    public function venues()
    {
        return $this->hasMany(Venue::class, 'owner_id');
    }
    public function rents()
    {
        return $this->hasManyThrough(Rent::class, Venue::class, 'owner_id', 'venue_id');
    }
}
