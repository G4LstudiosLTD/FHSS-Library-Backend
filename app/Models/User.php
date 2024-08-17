<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'phone',
        'first_name',
        'last_name',
        'email',
        'username',
        'role',
        'verification_token',
        'status',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function getSchoolId()
    {
        $user = Auth::user();
        
        if ($user) {
            if ($user->role == 'admin') {
                // Retrieve the school ID from the admins table
                $admin = Admin::where('user_id', $user->id)->first();
                return $admin ? $admin->school_id : null;
            } elseif ($user->role == 'student') {
                // Retrieve the school ID from the students table
                $student = Student::where('user_id', $user->id)->first();
                return $student ? $student->school_id : null;
            }
        }
    
        // Default return value if user is not authenticated or role is neither 'admin' nor 'student'
        return null;
    }
}
