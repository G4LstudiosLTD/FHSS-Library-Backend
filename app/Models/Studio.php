<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    use HasFactory;
    protected $table = "studios";
    protected $fillable = [
        'studio_name',
        'street_address',
        'local_government',
        'state',
        'description',
        'days_available',
        'time_available',
        'max_people',
        'studio_equipment',
        'studio_fee',
        'dedicated_producer',
        'studio_rule',
        'images',
    ];
}
