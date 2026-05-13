<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'total_marks',
        'obtained_marks',
        'percentage',
        'grade',
        'status'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function studentAdmission()
    {
        return $this->belongsTo(StudentAdmissionManagement::class, 'student_id', 'user_id');
    }
}
