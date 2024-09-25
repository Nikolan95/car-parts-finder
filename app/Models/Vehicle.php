<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicle';

    protected $fillable = [
        'vin',
        'brand',
        'model',
        'date',
        'manufactured',
        'prodrange',
        'market',
        'engine',
        'engine_nr',
        'engine_info',
        'transmission',
        'framecolor',
        'trimcolor',
        'filter_level',
        'catalogInfoToken',
        'catalogShortToken',
        'token',
        'partApplicabilityToken',
        'navigationTreeToken',
        'groupsToken'
    ];
}
