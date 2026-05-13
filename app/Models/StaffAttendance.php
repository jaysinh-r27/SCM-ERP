<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model
{
    protected $guarded = [];

    protected $casts = [
        'in_time' => 'datetime',
        'out_time' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
