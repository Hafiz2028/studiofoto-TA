<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'information',
        'dp_status',
        'dp_percentage',
        'dp_min',
        'service_event_id',
    ];
    public function serviceEvent()
    {
        return $this->belongsTo(ServiceEvent::class, 'service_event_id', 'id');
    }

    public function printPhotoDetails()
    {
        return $this->hasMany(PrintPhotoDetail::class, 'service_package_id', 'id');
    }

    public function addOnPackageDetails()
    {
        return $this->hasMany(AddOnPackageDetail::class, 'service_package_id', 'id');
    }
    public function servicePackageDetails()
    {
        return $this->hasMany(ServicePackageDetail::class, 'service_package_id', 'id');
    }
}
