<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class arm extends Model
{
    use HasFactory;

    protected $table = "arm";
    protected $fillable = [
        'school_id',
        'grade_id',
        'arm_name',
    ];
}
