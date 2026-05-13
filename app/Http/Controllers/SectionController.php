<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SectionController extends Controller
{
    protected $moduleName = 'Section Management';
    protected $moduleLink = '';
    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('section.index'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:create.section')->only('create', 'store');
        $this->middleware('permission:edit.section')->only('edit', 'update');
        $this->middleware('permission:delete.section')->only('destroy');
        $this->middleware('permission:view.section')->only('index', 'show');
    }

    public function index()
    {
        return view('section.index');
    }

    public function getData()
    {
        $data = Section::with('studentClass')->select('sections.*');

        return \Yajra\DataTables\Facades\DataTables::eloquent($data)
            ->addIndexColumn()
            ->addColumn('class_name', function ($row) {
                return $row->studentClass ? $row->studentClass->name : '-';
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge badge-success p-2">Active</span>';
                } else {
                    return '<span class="badge badge-danger p-2">Inactive</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $viewUrl = route('section.show', encrypt($row->id));
                $editUrl = route('section.edit', encrypt($row->id));
                $deleteUrl = route('section.destroy', encrypt($row->id));

                $html = '<div class="btn-group">';
                if (Gate::allows('view.section')) {
                    $html .= '<a href="' . $viewUrl . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                }
                if (Gate::allows('edit.section')) {
                    $html .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary ml-1 mr-1"><i class="fas fa-edit"></i></a>';
                }
                if (Gate::allows('delete.section')) {
                    $html .= '<button class="btn btn-sm btn-danger delete-record" data-url="' . $deleteUrl . '"><i class="fas fa-trash"></i></button>';
                }
                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $classes = \App\Models\StudentClass::where('status', 1)->get();
        return view('section.form', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:0,1',
        ]);

        $section = new Section();
        $section->name = $request->name;
        $section->class_id = $request->class_id;
        $section->capacity = $request->capacity;
        $section->status = $request->status;
        $section->save();

        return redirect()->route('section.index')->with('success', 'Section created successfully.');
    }

    public function show($id)
    {
        $section = Section::with('studentClass')->findOrFail(decrypt($id));
        return view('section.show', compact('section'));
    }

    public function edit($id)
    {
        $section = Section::findOrFail(decrypt($id));
        $classes = \App\Models\StudentClass::where('status', 1)->get();
        return view('section.form', compact('section', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $section = Section::findOrFail(decrypt($id));

        $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:0,1',
        ]);

        $section->name = $request->name;
        $section->class_id = $request->class_id;
        $section->capacity = $request->capacity;
        $section->status = $request->status;
        $section->save();

        return redirect()->route('section.index')->with('success', 'Section updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $section = Section::findOrFail(decrypt($id));
        $section->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Section deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Section deleted successfully.');
    }
}
