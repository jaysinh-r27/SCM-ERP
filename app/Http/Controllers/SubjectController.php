<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SubjectController extends Controller
{
    protected $moduleName = 'Subject Management';
    protected $moduleLink = '';
    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('subject.index'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:create.subject')->only('create', 'store');
        $this->middleware('permission:edit.subject')->only('edit', 'update');
        $this->middleware('permission:delete.subject')->only('destroy');
        $this->middleware('permission:view.subject')->only('index', 'show');
    }

    public function index()
    {
        return view('subject.index');
    }

    public function getData()
    {
        $data = Subject::select('subjects.*');

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
                $viewUrl = route('subject.show', encrypt($row->id));
                $editUrl = route('subject.edit', encrypt($row->id));
                $deleteUrl = route('subject.destroy', encrypt($row->id));

                $html = '<div class="btn-group">';
                if (Gate::allows('view.subject')) {
                    $html .= '<a href="' . $viewUrl . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                }
                if (Gate::allows('edit.subject')) {
                    $html .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary ml-1 mr-1"><i class="fas fa-edit"></i></a>';
                }
                if (Gate::allows('delete.subject')) {
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
        return view('subject.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:theory,practical',
        ]);

        $subject = new Subject();
        $subject->name = $request->name;
        $subject->type = $request->type;
        $subject->save();

        return redirect()->route('subject.index')->with('success', 'Subject created successfully.');
    }

    public function show($id)
    {
        $subject = Subject::findOrFail(decrypt($id));
        return view('subject.show', compact('subject'));
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail(decrypt($id));
        return view('subject.form', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail(decrypt($id));

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:theory,practical',
        ]);

        $subject->name = $request->name;
        $subject->type = $request->type;
        $subject->save();

        return redirect()->route('subject.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $subject = Subject::findOrFail(decrypt($id));
        $subject->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Subject deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Subject deleted successfully.');
    }
}
