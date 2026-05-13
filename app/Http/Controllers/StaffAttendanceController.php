<?php

namespace App\Http\Controllers;

use App\Models\StaffAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class StaffAttendanceController extends Controller
{

    protected $moduleName = 'Staff Attendance';
    protected $moduleLink = '';
    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('staff.attendance.index'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:create.staff.attendance')->only('create', 'store');
        $this->middleware('permission:edit.staff.attendance')->only('edit', 'update');
        $this->middleware('permission:delete.staff.attendance')->only('destroy');
        $this->middleware('permission:view.staff.attendance')->only('index', 'show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentDate = now()->format('Y-m-d');
        $currentTime = now()->toTimeString();

        $todayAttendance = StaffAttendance::where('staff_id', Auth::id())->whereDate('date', $currentDate)
            ->whereNull('out_time')
            ->orderBy('id', 'ASC')
            ->first();

        $attendances = StaffAttendance::where('staff_id', Auth::id())->whereDate('date', $currentDate)->get();

        return view(
            'attendance.staff_attendance.index',
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

        $todayAttendance = StaffAttendance::where('staff_id', Auth::id())->whereDate('date', $currentDate)
            ->whereNull('out_time')
            ->orderBy('id', 'ASC')
            ->first();

        if (!empty($todayAttendance)) {
            $attendance = StaffAttendance::find($todayAttendance->id);
        } else {
            $attendance = new StaffAttendance();
        }



        $attendance->date = $currentDate;

        if (!empty($todayAttendance)) {
            $attendance->out_time = $currentTime;
            $message = 'Check Out successfully!';
        } else {
            $attendance->staff_id = Auth::id();
            $attendance->in_time = $currentTime;
            $message = 'Check In successfully!';
        }

        $attendance->save();

        return redirect()->back()->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(StaffAttendance $staffAttendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StaffAttendance $staffAttendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StaffAttendance $staffAttendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StaffAttendance $staffAttendance)
    {
        //
    }

    public function list(Request $request)
    {

        if ($request->ajax()) {
            $data = StaffAttendance::where('staff_id', Auth::id())
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
                ->addColumn('duration', function ($row) {
                    return !empty($row->out_time) ? Carbon::parse($row->in_time)->diff($row->out_time)->format('%H:%I:%S') : '-';
                })
                ->make(true);
        }


        return view('attendance.staff_attendance.list');
    }
}
