<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintPhotoDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'print_service_event_id',
        'service_package_id',
        ];

    public function serviceEvent()
    {
        return $this->belongsTo(ServiceEvent::class, 'service_event_id', 'id');
    }

    public function servicePackage()
    {
        return $this->belongsTo(ServicePackage::class, 'service_package_id', 'id');
    }
}
