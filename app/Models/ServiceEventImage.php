<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceEventImage extends Model
{
    use HasFactory;
    protected $fillable =
    [
    'service_event_id',
    'image',
    ];
    public function serviceEvent()
    {
        return $this->belongsTo(ServiceEvent::class, 'service_event_id', 'id');
    }
}
