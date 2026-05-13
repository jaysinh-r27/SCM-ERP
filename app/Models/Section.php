<?php

namespace App\Models;

use App\Traits\TracksUserActions;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use TracksUserActions;
    protected $guarded = [];

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }
}
