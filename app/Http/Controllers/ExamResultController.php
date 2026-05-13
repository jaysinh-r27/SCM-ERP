<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamMark;
use App\Models\ExamResult;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ExamResultController extends Controller
{
    protected $moduleName = 'Exam Result Management';
    protected $moduleLink = '';

    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('exam-result.index'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:assign.marks')->only('marksEntryForm', 'storeMarks');
        $this->middleware('permission:view.results')->only('index', 'getData');
        $this->middleware('permission:generate.report.card')->only('generateReportCard');
    }

    public function index()
    {
        $exams = Exam::where('status', 1)->get();
        return view('exam_result.index', compact('exams'));
    }

    public function getData(Request $request)
    {
        $data = ExamResult::with(['exam', 'student', 'studentAdmission:id,user_id,class_id', 'studentAdmission.studentClasses:id,name']);

        if ($request->has('exam_id') && $request->exam_id != '') {
            $data->where('exam_id', $request->exam_id);
        }

        return Datatables::eloquent($data)
            ->addIndexColumn()
            ->addColumn('exam_name', function ($row) {
                return $row->exam->name ?? 'N/A';
            })
            ->addColumn('class', function ($row) {
                return $row->studentAdmission->studentClasses->name ?? 'N/A';
            })
            ->addColumn('student_name', function ($row) {
                return $row->student->name ?? 'N/A';
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                if (Gate::allows('generate.report.card')) {
                    $reportUrl = route('exam-result.report-card', encrypt($row->id));
                    $btn .= '<a href="' . $reportUrl . '" class="btn btn-info btn-sm" target="_blank"><i class="fas fa-file-pdf"></i> Report Card</a>';
                }
                if ($btn == '') {
                    $btn = '-';
                }
                return $btn;
            })
            ->rawColumns(['action', 'class'])
            ->make(true);
    }

    public function marksEntryForm(Request $request)
    {
        $exams = Exam::with('subject')->where('status', 1)->get();

        $subjects = Subject::where('status', 1)->get();
        $students = User::whereHas('roles', function ($query) {
            $query->where('roles.id', User::ROLE_STUDENT)
                ->where('roles.id', '!=', User::SUPERADMIN_ROLE_ID);
        })->get();

        $marks = [];
        $selected_exam_id = null;
        $selected_student_id = null;

        $examSubject = [];

        if ($request->has('exam_id') && $request->has('student_id')) {
            try {
                $selected_exam_id = decrypt($request->exam_id);
                $selected_student_id = decrypt($request->student_id);

                $examsSubjects = $exams->where('id', $selected_exam_id)->first();

                $examSubjectQuery = $examsSubjects->subject;
                $examSubject = $examSubjectQuery?->pluck('name', 'id')?->toArray();

                $existingMarks = ExamMark::where('exam_id', $selected_exam_id)
                    ->where('student_id', $selected_student_id)
                    ->get()
                    ->keyBy('subject_id');

                foreach ($examSubject as $subId => $subjectName) {
                    $marks[$subId] = $existingMarks->has($subId) ? $existingMarks[$subId]->obtained_marks : '';
                }
            } catch (\Exception $e) {
                return redirect()->route('exam-result.marks-entry')->with('error', 'Invalid parameters');
            }
        }

        return view('exam_result.marks_entry', compact('exams', 'subjects', 'students', 'marks', 'selected_exam_id', 'selected_student_id', 'examSubject'));
    }

    public function storeMarks(Request $request)
    {
        $request->validate([
            'exam_id' => 'required',
            'student_id' => 'required',
            'marks' => 'required|array',
            'marks.*' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            $exam_id = decrypt($request->exam_id);
            $student_id = decrypt($request->student_id);
        } catch (\Exception $e) {
            return back()->with('error', 'Invalid parameters');
        }

        $request->merge(['decrypted_exam_id' => $exam_id, 'decrypted_student_id' => $student_id]);
        $request->validate([
            'decrypted_exam_id' => 'exists:exams,id',
            'decrypted_student_id' => 'exists:users,id',
        ]);

        foreach ($request->marks as $subject_id => $obtained) {
            if ($obtained !== null && $obtained !== '') {
                ExamMark::updateOrCreate(
                    [
                        'exam_id' => $exam_id,
                        'student_id' => $student_id,
                        'subject_id' => $subject_id
                    ],
                    [
                        'max_marks' => 100,
                        'obtained_marks' => $obtained
                    ]
                );
            } else {
                ExamMark::where('exam_id', $exam_id)
                    ->where('student_id', $student_id)
                    ->where('subject_id', $subject_id)
                    ->delete();
            }
        }

        return redirect()->route('exam-result.marks-entry', [
            'exam_id' => encrypt($exam_id),
            'student_id' => encrypt($student_id)
        ])->with('success', 'Marks saved successfully.');
    }

    public function generateReportCard($id)
    {
        $result_id = decrypt($id);
        $result = ExamResult::with(['exam', 'student', 'studentAdmission:id,user_id,class_id', 'studentAdmission.studentClasses:id,name'])->findOrFail($result_id);

        $marks = ExamMark::with('subject')
            ->where('exam_id', $result->exam_id)
            ->where('student_id', $result->student_id)
            ->get();

        $pdf = Pdf::loadView('exam_result.report_card', compact('result', 'marks'));
        return $pdf->download('report-card-' . $result->student->name . '.pdf');
    }
}
