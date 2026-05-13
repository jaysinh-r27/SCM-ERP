<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\AcademicSession;
use App\Models\Subject;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    protected $moduleName = 'Exam Management';
    protected $moduleLink = '';

    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('exam.index'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:create.exam')->only('create', 'store');
        $this->middleware('permission:edit.exam')->only('edit', 'update');
        $this->middleware('permission:delete.exam')->only('destroy');
        $this->middleware('permission:view.exam')->only('index', 'getData');
    }

    public function index()
    {
        return view('exam.index');
    }

    public function getData(Request $request)
    {
        $data = Exam::with('session')->select('exams.*');
        return Datatables::eloquent($data)
            ->addIndexColumn()
            ->addColumn('session_name', function ($row) {
                return $row->session ? $row->session->name : 'N/A';
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                if (Gate::allows('edit.exam')) {
                    $editUrl = route('exam.edit', encrypt($row->id));
                    $btn .= '<a href="' . $editUrl . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
                }
                if (Gate::allows('delete.exam')) {
                    $deleteUrl = route('exam.destroy', encrypt($row->id));
                    $btn .= ' <button type="button" class="btn btn-danger btn-sm delete-record" data-url="' . $deleteUrl . '"><i class="fas fa-trash"></i></button>';
                }
                if ($btn == '') {
                    $btn = '-';
                }
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $sessions = AcademicSession::where('status', 1)->get();
        $subjects = Subject::where('status', 1)->get();

        return view('exam.form', compact('sessions', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:0,1',
            'subject_id' => 'required|array',
            'subject_id.*' => 'exists:subjects,id',
        ]);

        $exam = Exam::create($request->all());
        $exam->subject()->attach($request->subject_id);

        return redirect()->route('exam.index')->with('success', 'Exam created successfully.');
    }

    public function edit($id)
    {
        $exam = Exam::findOrFail(decrypt($id));
        $sessions = AcademicSession::where('status', 1)->get();
        $subjects = Subject::where('status', 1)->get();
        $examSubjects = $exam->subject()->pluck('subject_id')->toArray();

        return view('exam.form', compact('exam', 'sessions', 'subjects', 'examSubjects'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:0,1',
            'subject_id' => 'required|array',
            'subject_id.*' => 'exists:subjects,id',
        ]);

        $exam = Exam::findOrFail(decrypt($id));
        $exam->update($request->all());
        $exam->subject()->sync($request->subject_id);

        return redirect()->route('exam.index')->with('success', 'Exam updated successfully.');
    }

    public function destroy($id)
    {
        $exam = Exam::findOrFail(decrypt($id));
        $exam->delete();

        return response()->json(['status' => 'success', 'message' => 'Exam deleted successfully.']);
    }
}
