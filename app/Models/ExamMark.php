<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\ExamMarkObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([ExamMarkObserver::class])]
class ExamMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'subject_id',
        'max_marks',
        'obtained_marks'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
