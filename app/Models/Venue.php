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
    public function venueImages()
    {
        return $this->hasMany(VenueImage::class, 'venue_id', 'id');
    }
    public function openingHours()
    {
        return $this->hasMany(OpeningHour::class, 'venue_id', 'id');
    }
    public function activeOpeningHours()
    {
        return $this->hasMany(OpeningHour::class, 'venue_id', 'id')->active();
    }
    public function uniqueActiveDays()
    {
        return $this->activeOpeningHours()
            ->with('day')
            ->get()
            ->pluck('day')
            ->unique('id')
            ->sortBy(function ($day) {
                $order = [1, 2, 3, 4, 5, 6, 7];
                return array_search($day->id, $order);
            });
    }
    public function paymentMethodDetails()
    {
        return $this->hasMany(PaymentMethodDetail::class, 'venue_id', 'id');
    }
}
