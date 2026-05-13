<?php

namespace App\Http\Controllers;

use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ClassManagementController extends Controller
{
    protected $moduleName = 'Class Management';
    protected $moduleLink = '';
    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('student.class.index'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:create.class')->only('create', 'store');
        $this->middleware('permission:edit.class')->only('edit', 'update');
        $this->middleware('permission:delete.class')->only('destroy');
        $this->middleware('permission:view.class')->only('index', 'show');
    }

    public function index()
    {
        return view('student_class.index');
    }

    public function getData()
    {
        $data = StudentClass::select('student_classes.*');

        return \Yajra\DataTables\Facades\DataTables::eloquent($data)
            ->addIndexColumn()
            ->editColumn('type', function ($row) {
                return ucfirst($row->type);
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge badge-success p-2">Active</span>';
                } else {
                    return '<span class="badge badge-danger p-2">Inactive</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $viewUrl = route('student.class.show', encrypt($row->id));
                $editUrl = route('student.class.edit', encrypt($row->id));
                $deleteUrl = route('student.class.destroy', encrypt($row->id));

                $html = '<div class="btn-group">';
                if (Gate::allows('view.class')) {
                    $html .= '<a href="' . $viewUrl . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                }
                if (Gate::allows('edit.class')) {
                    $html .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary ml-1 mr-1"><i class="fas fa-edit"></i></a>';
                }
                if (Gate::allows('delete.class')) {
                    $html .= '<button class="btn btn-sm btn-danger delete-record" data-url="' . $deleteUrl . '"><i class="fas fa-trash"></i></button>';
                }
                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function create()
    {
        return view('student_class.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:school,college',
            'status' => 'required|in:0,1',
        ]);

        $studentClass = new StudentClass();
        $studentClass->name = $request->name;
        $studentClass->type = $request->type;
        $studentClass->status = $request->status;
        $studentClass->save();

        return redirect()->route('student.class.index')->with('success', 'Class created successfully.');
    }

    public function show($id)
    {
        $studentClass = StudentClass::findOrFail(decrypt($id));
        return view('student_class.show', compact('studentClass'));
    }

    public function edit($id)
    {
        $studentClass = StudentClass::findOrFail(decrypt($id));
        return view('student_class.form', compact('studentClass'));
    }

    public function update(Request $request, $id)
    {
        $studentClass = StudentClass::findOrFail(decrypt($id));

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:school,college',
            'status' => 'required|in:0,1',
        ]);

        $studentClass->name = $request->name;
        $studentClass->type = $request->type;
        $studentClass->status = $request->status;
        $studentClass->save();

        return redirect()->route('student.class.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $studentClass = StudentClass::findOrFail(decrypt($id));
        $studentClass->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Class deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Class deleted successfully.');
    }
}
