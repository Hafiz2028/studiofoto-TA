<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'venue_id',
        'catalog',
        'description',
        'service_type_id'
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'id');
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id', 'id');
    }

    public function servicePackages()
    {
        return $this->hasMany(ServicePackage::class, 'service_event_id', 'id');
    }

    public function serviceEventImages()
    {
        return $this->hasMany(ServiceEventImage::class, 'service_event_id', 'id');
    }


}
