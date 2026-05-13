<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSubject extends Model
{
    protected $guarded = [];

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
