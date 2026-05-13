<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'academic_session_id',
        'start_date',
        'end_date',
        'status'
    ];

    public function session()
    {
        return $this->belongsTo(AcademicSession::class, 'academic_session_id');
    }

    public function marks()
    {
        return $this->hasMany(ExamMark::class, 'exam_id');
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class, 'exam_id');
    }

    public function subject()
    {
        return $this->belongsToMany(Subject::class, 'exam_subjects', 'exam_id', 'subject_id');
    }
}
