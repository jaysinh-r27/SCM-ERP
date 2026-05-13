<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\TracksUserActions;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'phone', 'created_by', 'updated_by', 'status', 'deleted_at'])]
#[Hidden(['password', 'remember_token', 'deleted_at', 'updated_at', 'created_at'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, TracksUserActions;

    const SUPERADMIN_ROLE_ID = 1;
    const ROLE_STUDENT = 4;
    const ROLE_STAFF = 7;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id');
    }

    public function isSuperAdmin()
    {
        return $this->roles()->where('roles.id', self::SUPERADMIN_ROLE_ID)->exists();
    }

    public function getRoleAttribute()
    {
        return $this->roles->first();
    }

    public function staffProfile()
    {
        return $this->hasOne(StaffProfile::class);
    }

    public function studentFees()
    {
        return $this->hasMany(StudentFee::class, 'student_id');
    }

    public function studentAdmission()
    {
        return $this->hasOne(StudentAdmissionManagement::class);
    }
}
