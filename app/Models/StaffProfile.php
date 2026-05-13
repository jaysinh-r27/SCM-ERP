<?php

namespace App\Models;

use App\Traits\TracksUserActions;
use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
