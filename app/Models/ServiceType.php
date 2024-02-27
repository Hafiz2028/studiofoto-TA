<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class ServiceType extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable =[
        'service_name',
        'service_slug',
        // 'ordering'

    ];
    public function sluggable(): array
    {
        return [
            'service_slug' => [
                'source' => 'service_name'
            ]
        ];
    }
}
