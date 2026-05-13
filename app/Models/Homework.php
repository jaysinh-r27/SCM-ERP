<?php

namespace App\Models;

use App\Traits\TracksUserActions;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;
use App\Models\StudentClass;
use App\Models\Section;

class Homework extends Model
{
    use TracksUserActions;
    protected $guarded = [];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(StudentClass::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
