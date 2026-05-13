<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AcademicSession;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\FeeCategory;
use App\Models\StaffAttendance;
use App\Models\StudentAttendance;
use App\Models\StudentClass;
use App\Models\StudentFee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function studentListReport(Request $request)
    {
        $this->authorize('view.student.list.report');

        view()->share([
            'moduleName' => 'Student List Report'
        ]);

        $academic_sessions = AcademicSession::all();
        $classes = StudentClass::all();

        if ($request->ajax()) {
            $query = User::select(['id', 'name', 'email', 'phone', 'status'])
                ->with([
                    'studentAdmission:id,user_id,class_id,admission_date',
                    'studentAdmission.studentClasses:id,name'
                ])
                ->whereHas('roles', function ($q) {
                    $q->where('roles.id', User::ROLE_STUDENT);
                })
                ->where('status', 1);

            if ($request->filled('class_id')) {
                $query->whereHas('studentAdmission', function ($q) use ($request) {
                    $q->where('class_id', $request->class_id);
                });
            }

            if ($request->filled('academic_session_id')) {
                $query->whereHas('studentAdmission', function ($q) use ($request) {
                    $q->where('academic_session_id', $request->academic_session_id);
                });
            }

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('class', function ($row) {
                    return $row->studentAdmission && $row->studentAdmission->studentClasses
                        ? $row->studentAdmission->studentClasses->name
                        : '-';
                })
                ->addColumn('admission_date', function ($row) {
                    return $row->studentAdmission && $row->studentAdmission->admission_date
                        ? date('d-m-Y', strtotime($row->studentAdmission->admission_date))
                        : '-';
                })
                ->rawColumns(['name', 'email', 'phone', 'class', 'admission_date'])
                ->make(true);
        }

        return view('reports.student_list_report', compact('academic_sessions', 'classes'));
    }

    public function examResultReport(Request $request)
    {
        $this->authorize('view.exam.result.report');

        view()->share([
            'moduleName' => 'Exam Result Report'
        ]);

        $exams = Exam::where('status', 1)->get();
        $classes = StudentClass::where('status', 1)->get();

        if ($request->ajax()) {

            $query = ExamResult::select(['id', 'student_id', 'exam_id', 'total_marks', 'obtained_marks', 'percentage', 'grade', 'status'])
                ->with([
                    'studentAdmission:id,name,user_id,class_id,admission_date',
                    'studentAdmission.studentClasses:id,name',
                    'exam'
                ]);

            if ($request->filled('class_id')) {
                $query->whereHas('studentAdmission', function ($q) use ($request) {
                    $q->whereHas('studentClasses', function ($sq) use ($request) {
                        $sq->where('id', $request->class_id);
                    });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('exam_id')) {
                $query->where('exam_id', $request->exam_id);
            }

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('student_id', function ($row) {
                    return isset($row->studentAdmission) ? $row->studentAdmission->name : '-';
                })
                ->addColumn('exam_id', function ($row) use ($exams) {
                    return isset($row->exam) ? $row->exam->name : '-';
                })
                ->addColumn('class', function ($row) {
                    return $row->studentAdmission && $row->studentAdmission->studentClasses
                        ? $row->studentAdmission->studentClasses->name
                        : '-';
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1 ? '<span class="badge badge-success">Pass</span>' : '<span class="badge badge-danger">Fail</span>';
                })
                ->editColumn('grade', function ($row) {
                    return $row->grade;
                })
                ->editColumn('obtained_marks', function ($row) {
                    return $row->obtained_marks;
                })
                ->editColumn('total_marks', function ($row) {
                    return $row->total_marks;
                })
                ->editColumn('percentage', function ($row) {
                    return $row->percentage;
                })
                ->rawColumns(['student_id', 'exam_id', 'class', 'status', 'grade', 'obtained_marks', 'total_marks', 'percentage'])
                ->make(true);
        }

        return view('reports.exam_result_report', compact('exams', 'classes'));
    }

    public function feeReport(Request $request)
    {
        $this->authorize('view.fee.report');

        view()->share([
            'moduleName' => 'Fee Report'
        ]);

        $classes = StudentClass::where('status', 1)->pluck('name', 'id')->toArray();

        $feecats = FeeCategory::where('status', 1)->pluck('name', 'id')->toArray();

        $studentQuery = User::select(['id', 'name', 'email', 'phone', 'status'])
            ->whereHas('roles', function ($q) {
                $q->where('roles.id', User::ROLE_STUDENT);
            })
            ->where('status', 1);

        $students = (clone $studentQuery)->pluck('name', 'id')->toArray();

        if ($request->ajax()) {
            $studenetId = (clone $studentQuery)->pluck('id')->toArray();

            $fees = StudentFee::with([
                'category',
                'payments',
                'studentAdmission:id,user_id,class_id,name,admission_date',
                'studentAdmission.studentClasses:id,name'
            ])
                ->whereIn('student_id', $studenetId);

            if ($request->filled('class_id')) {
                $fees->whereHas('studentAdmission', function ($q) use ($request) {
                    $q->whereHas('studentClasses', function ($sq) use ($request) {
                        $sq->where('id', $request->class_id);
                    });
                });
            }

            if ($request->filled('status')) {
                $fees->where('status', $request->status);
            }

            if ($request->filled('student_id')) {
                $fees->whereHas('studentAdmission', function ($q) use ($request) {
                    $q->where('user_id', $request->student_id);
                });
            }

            if ($request->filled('fee_cat_id')) {
                $fees->whereHas('category', function ($q) use ($request) {
                    $q->where('id', $request->fee_cat_id);
                });
            }

            return DataTables::eloquent($fees)
                ->addIndexColumn()
                ->addColumn('student_name', function ($row) {
                    return $row->studentAdmission?->name ?? '-';
                })
                ->addColumn('class', function ($row) {
                    return $row->studentAdmission?->studentClasses?->name ?? '-';
                })
                ->addColumn('fee_category', function ($row) {
                    return $row->category?->name ?? '-';
                })
                ->addColumn('total_amount', function ($row) {
                    return number_format($row->amount, 2);
                })
                ->addColumn('paid_amount', function ($row) {
                    return number_format($row->paid_amount, 2);
                })
                ->addColumn('remaining_amount', function ($row) {
                    return number_format(($row->amount - $row->paid_amount), 2);
                })
                ->addColumn('due_date', function ($row) {
                    return $row->due_date
                        ? \Carbon\Carbon::parse($row->due_date)->format('Y-m-d')
                        : 'N/A';
                })
                ->editColumn('status', function ($row) {

                    if ($row->status == 'paid') {
                        return '<span class="badge badge-success">Paid</span>';
                    }

                    if ($row->status == 'partial') {
                        return '<span class="badge badge-warning">Partial</span>';
                    }

                    return '<span class="badge badge-danger">Pending</span>';
                })
                ->addColumn('payment_method', function ($row) {

                    if ($row->payments->count() > 0) {

                        $html = '<ul class="list-unstyled mb-0">';

                        foreach ($row->payments as $payment) {

                            $html .= '
                    <li class="mb-1">
                        ' . $payment->payment_method . '
                    </li>';
                        }

                        $html .= '</ul>';

                        return $html;
                    }

                    return '<em>No payments</em>';
                })
                ->addColumn('payments_receipts', function ($row) {

                    if ($row->payments->count() > 0) {

                        $html = '<ul class="list-unstyled mb-0">';

                        foreach ($row->payments as $payment) {

                            $html .= '
                    <li class="mb-1">
                        ' . number_format($payment->amount_paid, 2) . ' on
                        ' . \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') . '
                    </li>';
                        }

                        $html .= '</ul>';

                        return $html;
                    }

                    return '<em>No payments</em>';
                })
                ->rawColumns([
                    'student_name',
                    'class',
                    'fee_category',
                    'total_amount',
                    'paid_amount',
                    'remaining_amount',
                    'due_date',
                    'status',
                    'payment_method',
                    'payments_receipts',
                ])
                ->make(true);
        }

        return view('reports.fee_report', compact('students', 'classes', 'feecats'));
    }

    public function attendanceReport(Request $request)
    {
        $this->authorize('view.attendance.report');

        view()->share([
            'moduleName' => 'Attendance Report'
        ]);

        $type = $request->input('type');
        if (!empty($type) && decrypt($type) == User::ROLE_STUDENT) {
            return $this->studentAttendaceReportData($request);
        } else if (!empty($type) && decrypt($type) == User::ROLE_STAFF) {
            return $this->staffAttendaceReportData($request);
        } else {
            return $this->studentAttendaceReportData($request);
        }
    }

    public function studentAttendaceReportData($request)
    {
        $fromDate = $request->input('from_date');
        $toDate   = $request->input('to_date');
        $class_id   = $request->input('class_id');
        $student_id   = $request->input('student_id');
        $type   = $request->input('type');

        $classes = StudentClass::where('status', 1)->pluck('name', 'id')->toArray();

        $studentsQuery =  User::select(['id', 'name', 'email', 'phone', 'status'])
            ->with([
                'studentAdmission:id,user_id,class_id,admission_date',
                'studentAdmission.studentClasses:id,name'
            ]);

        $studenetData = (clone $studentsQuery)->when(!empty($type), function ($q) use ($type) {
            $q->whereHas('roles', function ($q) use ($type) {
                $q->where('roles.id', decrypt($type));
            });
        })->get();

        $students = (clone $studentsQuery)->whereHas('roles', function ($q) {
            $q->where('roles.id', User::ROLE_STUDENT);
        })
            ->when(!empty($class_id), function ($q) use ($class_id) {
                $q->whereHas('studentAdmission', function ($q) use ($class_id) {
                    $q->where('class_id', $class_id);
                });
            })
            ->where('id', $student_id)
            ->where('status', 1)
            ->get();

        $startOfMonth = $request->filled('from_date')
            ? Carbon::parse($request->from_date)
            : Carbon::now()->startOfMonth();

        $endOfMonth = $request->filled('to_date')
            ? Carbon::parse($request->to_date)
            : Carbon::now()->endOfMonth();

        if ($request->ajax()) {

            $attendances = StudentAttendance::with(['student:id,name'])
                ->whereIn('student_id', $students->pluck('id'))
                // ->whereBetween('date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
                ->get()
                ->groupBy('date');

            $finalData = [];

            for ($date = $endOfMonth->copy(); $date->gte($startOfMonth); $date->subDay()) {
                $currentDateStr = $date->format('Y-m-d');
                $displayDate    = $date->format('d-m-Y');

                if (!empty($students)) {
                    foreach ($students as $student) {
                        $attendance = collect($attendances[$currentDateStr] ?? [])
                            ->where('student_id', $student->id)
                            ->first();

                        if (!empty($attendance)) {
                            $finalData[] = [
                                'date'     => $displayDate,
                                'name'     => $student->name,
                                'class'    => $student->studentAdmission?->studentClasses?->name ?? '-',
                                'status'   => '<span class="badge bg-success">Present</span>',
                            ];
                        } else {
                            $finalData[] = [
                                'date'     => $displayDate,
                                'name'     => $student->name,
                                'class'    => $student->studentAdmission?->studentClasses?->name ?? '-',
                                'status'   => '<span class="badge bg-danger">Absent</span>',
                            ];
                        }
                    }
                }
            }

            if (empty($finalData)) {
                return DataTables::of([])->make(true);
            }

            return DataTables::of($finalData)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return $row['date'];
                })
                ->addColumn('name', function ($row) {
                    return $row['name'];
                })
                ->addColumn('class', function ($row) {
                    return $row['class'];
                })
                ->addColumn('status', function ($row) {
                    return $row['status'];
                })
                ->rawColumns(['date', 'name', 'class', 'status'])
                ->make(true);
        }
        return view('reports.attendance.student_attendance_report', compact('classes', 'studenetData', 'fromDate', 'toDate', 'class_id', 'type', 'student_id'));
    }

    public function staffAttendaceReportData(Request $request)
    {
        $fromDate  = $request->input('from_date');
        $toDate    = $request->input('to_date');
        $staffId   = $request->input('staff_id');
        $type      = $request->input('type');

        $staffsQuery = User::select(['id', 'name'])
            ->whereHas('roles', function ($q) {
                $q->where('roles.id', User::ROLE_STAFF);
            })
            ->where('status', 1);

        $staffList = (clone $staffsQuery)->pluck('name', 'id')->toArray();

        $startDate = $request->filled('from_date')
            ? Carbon::parse($fromDate)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->filled('to_date')
            ? Carbon::parse($toDate)->endOfDay()
            : Carbon::now()->endOfDay();

        if ($request->ajax()) {

            $attendanceQuery = StaffAttendance::with(['staff:id,name'])
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

            if (!empty($staffId)) {
                $attendanceQuery->where('staff_id', $staffId);
                $staffMembers = (clone $staffsQuery)->where('id', $staffId)->get();
            } else {
                $staffIds = (clone $staffsQuery)->pluck('id');
                $attendanceQuery->whereIn('staff_id', $staffIds);
                $staffMembers = (clone $staffsQuery)->get();
            }

            $attendances = $attendanceQuery->get()->groupBy([
                fn($r) => $r->date,
                fn($r) => $r->staff_id,
            ]);

            $finalData = [];

            for ($date = $endDate->copy()->startOfDay(); $date->gte($startDate->copy()->startOfDay()); $date->subDay()) {
                $currentDateStr = $date->format('Y-m-d');
                $displayDate    = $date->format('d-m-Y');

                foreach ($staffMembers as $staff) {

                    $dayRecords = collect($attendances[$currentDateStr][$staff->id] ?? []);

                    if ($dayRecords->isEmpty()) {
                        $finalData[] = [
                            'staff_name'       => $staff->name,
                            'display_date'     => $displayDate,
                            'in_time_display'  => '-',
                            'out_time_display' => '-',
                            'duration'         => '-',
                            'status'           => '<span class="badge badge-danger">Absent</span>',
                        ];
                        continue;
                    }

                    $inTimes      = [];
                    $outTimes     = [];
                    $totalSeconds = 0;
                    $hasOpenShift = false;

                    foreach ($dayRecords as $record) {
                        $inTimes[] = $record->in_time
                            ? Carbon::parse($record->in_time)->format('h:i A')
                            : '-';

                        if (!empty($record->out_time)) {
                            $outTimes[]    = Carbon::parse($record->out_time)->format('h:i A');
                            $totalSeconds += Carbon::parse($record->in_time)->diffInSeconds(Carbon::parse($record->out_time));
                        } else {
                            $outTimes[]   = '-';
                            $hasOpenShift = true;
                        }
                    }

                    if ($hasOpenShift && $totalSeconds === 0) {
                        $durationDisplay = '<span class="badge badge-warning">In Progress</span>';
                    } else {
                        $hours   = intdiv($totalSeconds, 3600);
                        $minutes = intdiv($totalSeconds % 3600, 60);
                        $seconds = $totalSeconds % 60;
                        $durationDisplay = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                        if ($hasOpenShift) {
                            $durationDisplay .= ' <span class="badge badge-warning">+In Progress</span>';
                        }
                    }

                    $finalData[] = [
                        'staff_name'       => $staff->name,
                        'display_date'     => $displayDate,
                        'in_time_display'  => implode('<br>', $inTimes),
                        'out_time_display' => implode('<br>', $outTimes),
                        'duration'         => $durationDisplay,
                        'status'           => '<span class="badge badge-success">Present</span>',
                    ];
                }
            }

            return DataTables::of($finalData)
                ->addIndexColumn()
                ->addColumn('staff_name', fn($row) => $row['staff_name'])
                ->addColumn('display_date', fn($row) => $row['display_date'])
                ->addColumn('in_time_display', fn($row) => $row['in_time_display'])
                ->addColumn('out_time_display', fn($row) => $row['out_time_display'])
                ->addColumn('duration', fn($row) => $row['duration'])
                ->addColumn('status', fn($row) => $row['status'])
                ->rawColumns(['in_time_display', 'out_time_display', 'duration', 'status'])
                ->make(true);
        }

        return view('reports.attendance.staff_attendance_report', compact(
            'staffList',
            'staffId',
            'fromDate',
            'toDate',
            'type'
        ));
    }
}
