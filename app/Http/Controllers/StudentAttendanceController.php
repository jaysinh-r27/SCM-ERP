<?php

namespace App\Http\Controllers;

use App\Models\StudentAttendance;
use App\Models\StudentClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class StudentAttendanceController extends Controller
{

    protected $moduleName = 'Student Attendance';
    protected $moduleLink = '';
    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('student.attendance.index'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:create.student.attendance')->only('create', 'store');
        $this->middleware('permission:edit.student.attendance')->only('edit', 'update');
        $this->middleware('permission:delete.student.attendance')->only('destroy');
        $this->middleware('permission:view.student.attendance')->only('index', 'show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentDate = now()->format('Y-m-d');
        $currentTime = now()->toTimeString();

        $todayAttendance = StudentAttendance::where('student_id', Auth::id())->whereDate('date', $currentDate)
            ->whereNull('out_time')
            ->orderBy('id', 'ASC')
            ->first();

        $attendances = StudentAttendance::where('student_id', Auth::id())->whereDate('date', $currentDate)->get();

        return view(
            'attendance.student_attendance.index',
            compact('todayAttendance', 'attendances')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentDate = now()->format('Y-m-d');
        $currentTime = now()->toTimeString();

        $todayAttendance = StudentAttendance::where('student_id', Auth::id())->whereDate('date', $currentDate)
            ->whereNull('out_time')
            ->orderBy('id', 'ASC')
            ->first();

        if (!empty($todayAttendance)) {
            $attendance = StudentAttendance::find($todayAttendance->id);
        } else {
            $attendance = new StudentAttendance();
        }



        $attendance->date = $currentDate;

        if (!empty($todayAttendance)) {
            $attendance->out_time = $currentTime;
            $message = 'Check Out successfully!';
        } else {
            $attendance->student_id = Auth::id();
            $attendance->in_time = $currentTime;
            $message = 'Check In successfully!';
        }

        $attendance->save();

        return redirect()->back()->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentAttendance $studentAttendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentAttendance $studentAttendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentAttendance $studentAttendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentAttendance $studentAttendance)
    {
        //
    }

    public function list(Request $request)
    {

        if ($request->ajax()) {
            $data = StudentAttendance::where('student_id', Auth::id())
                ->latest()
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return $row->date
                        ? Carbon::parse($row->date)->format('d-m-Y')
                        : '-';
                })
                ->addColumn('in_time', function ($row) {
                    return $row->in_time
                        ? Carbon::parse($row->in_time)->format('h:i A')
                        : '-';
                })
                ->addColumn('out_time', function ($row) {
                    return $row->out_time
                        ? Carbon::parse($row->out_time)->format('h:i A')
                        : '-';
                })
                ->make(true);
        }


        return view('attendance.student_attendance.list');
    }

    public function monthlyReport(Request $request)
    {
        view()->share([
            'moduleName' => 'Student Attendance Monthly Report',
        ]);

        $fromDate = $request->input('from_date');
        $toDate   = $request->input('to_date');
        $class_id   = $request->input('class_id');
        $student_id   = $request->input('student_id');

        $classes = StudentClass::where('status', 1)->pluck('name', 'id')->toArray();

        $studentsQuery =  User::select(['id', 'name', 'email', 'phone', 'status'])
            ->with([
                'studentAdmission:id,user_id,class_id,admission_date',
                'studentAdmission.studentClasses:id,name'
            ]);

        $studenetData = $studentsQuery->whereHas('roles', function ($q) {
            $q->where('roles.id', User::ROLE_STUDENT);
        })->get();

        $students = $studentsQuery->whereHas('roles', function ($q) {
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

        return view('attendance.student_attendance.report', compact('classes', 'studenetData', 'fromDate', 'toDate', 'class_id', 'student_id'));
    }
}
