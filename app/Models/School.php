<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'address',
        'state',
        'city',
        'phone',
        'email',
        'short_code',
        'logo'
    ];

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
