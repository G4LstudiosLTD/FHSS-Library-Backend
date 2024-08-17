<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject_Topic extends Model
{
    use HasFactory;

    protected $table = "subject_topics";
    protected $fillable = [
        'school_id',
        'grade_id',
        'subject_id',
        'topic',
        'description',
        'description',
        'file'
    ];
}
