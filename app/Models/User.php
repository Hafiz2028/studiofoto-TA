<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'handphone',
        'address',
        'picture',
        'role',
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
        'password' => 'hashed',
    ];

    public function getPictureAttribute($value)
    {
        $defaultAvatar = asset('/images/users/default-avatar.png');
        if ($this->role === 'admin') {
            return $value ? asset('/images/users/admins/' . $value) : $defaultAvatar;
        } elseif ($this->role === 'owner') {
            return $value ? asset('/images/users/owners/' . $value) : $defaultAvatar;
        } elseif ($this->role === 'customer') {
            return $value ? asset('/images/users/customers/' . $value) : $defaultAvatar;
        } else {
            return $defaultAvatar;
        }
    }
    public function owner()
    {
        return $this->hasOne(Owner::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}
