<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = "students";
    protected $fillable = [
        'school_id',
        'user_id',
        'grade_id',
        'email',
        'first_name',
        'last_name',
        'dob',
        'gender',
    ];
}
