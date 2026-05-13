<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    protected $guarded = [];

    protected $casts = [
        'in_time' => 'datetime',
        'out_time' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'id');
    }
}
