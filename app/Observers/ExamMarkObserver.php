<?php

namespace App\Observers;

use App\Models\ExamMark;
use App\Models\ExamResult;

class ExamMarkObserver
{
    public function saved(ExamMark $examMark): void
    {
        $this->updateResult($examMark);
    }

    public function deleted(ExamMark $examMark): void
    {
        $this->updateResult($examMark);
    }

    private function updateResult(ExamMark $examMark): void
    {
        $exam_id = $examMark->exam_id;
        $student_id = $examMark->student_id;

        $marks = ExamMark::where('exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->get();

        if ($marks->isEmpty()) {
            ExamResult::where('exam_id', $exam_id)
                ->where('student_id', $student_id)
                ->delete();
            return;
        }

        $total_marks = $marks->sum('max_marks');
        $obtained_marks = $marks->sum('obtained_marks');

        $percentage = $total_marks > 0 ? ($obtained_marks / $total_marks) * 100 : 0;

        $grade = 'F';
        $status = 0;

        if ($percentage >= 90) {
            $grade = 'A+';
            $status = 1;
        } elseif ($percentage >= 80) {
            $grade = 'A';
            $status = 1;
        } elseif ($percentage >= 70) {
            $grade = 'B';
            $status = 1;
        } elseif ($percentage >= 60) {
            $grade = 'C';
            $status = 1;
        } elseif ($percentage >= 35) {
            $grade = 'D';
            $status = 1;
        }

        ExamResult::updateOrCreate(
            [
                'exam_id' => $exam_id,
                'student_id' => $student_id,
            ],
            [
                'total_marks' => $total_marks,
                'obtained_marks' => $obtained_marks,
                'percentage' => $percentage,
                'grade' => $grade,
                'status' => $status,
            ]
        );
    }
}
