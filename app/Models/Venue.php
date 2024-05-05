<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'address',
        'imb',
        'dp_percentage',
        'information',
        'phone_number',
        'village_id',
        'map_link',
        'reject_note',
        'owner_id'

    ];
    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id', 'id');
    }
    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id', 'id');
    }

    public function serviceEvents()
    {
        return $this->hasMany(ServiceEvent::class, 'venue_id', 'id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'venue_id', 'id');
    }

    public function venueImages()
    {
        return $this->hasMany(VenueImage::class, 'venue_id', 'id');
    }

    public function openingHours()
    {
        return $this->hasMany(OpeningHour::class, 'venue_id', 'id');
    }

    public function paymentMethodDetails()
    {
        return $this->hasMany(PaymentMethodDetail::class, 'venue_id', 'id');
    }
}
