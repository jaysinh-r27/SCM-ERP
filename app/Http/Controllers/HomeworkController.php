<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use App\Models\Subject;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class HomeworkController extends Controller
{
    protected $moduleName = 'Homework Management';
    protected $moduleLink = '';

    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('homework.index'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:create.homework')->only('create', 'store');
        $this->middleware('permission:edit.homework')->only('edit', 'update');
        $this->middleware('permission:delete.homework')->only('destroy');
        $this->middleware('permission:view.homework')->only('index', 'show', 'getData');
    }

    public function index()
    {
        return view('homework.index');
    }

    public function getData()
    {
        $data = Homework::with(['subject', 'class'])->select('homework.*');

        return DataTables::eloquent($data)
            ->addIndexColumn()
            ->editColumn('class_id', function ($row) {
                return $row->class ? $row->class->name : '-';
            })
            ->editColumn('subject_id', function ($row) {
                return $row->subject ? $row->subject->name : '-';
            })
            ->editColumn('date', function ($row) {
                return date('d-m-Y', strtotime($row->date));
            })
            ->editColumn('submission_date', function ($row) {
                return date('d-m-Y', strtotime($row->submission_date));
            })
            ->addColumn('files', function ($row) {
                $html = '';
                if ($row->file_upload) {
                    $html .= '<a href="' . Storage::url($row->file_upload) . '" target="_blank" class="badge badge-info p-2 mr-1">Homework File</a>';
                }
                if ($row->study_material_upload) {
                    $html .= '<a href="' . Storage::url($row->study_material_upload) . '" target="_blank" class="badge badge-secondary p-2">Study Material</a>';
                }
                return $html ?: '-';
            })
            ->addColumn('action', function ($row) {
                $viewUrl = route('homework.show', encrypt($row->id));
                $editUrl = route('homework.edit', encrypt($row->id));
                $deleteUrl = route('homework.destroy', encrypt($row->id));

                $html = '<div class="btn-group">';
                if (Gate::allows('view.homework')) {
                    $html .= '<a href="' . $viewUrl . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                }
                if (Gate::allows('edit.homework')) {
                    $html .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary ml-1 mr-1"><i class="fas fa-edit"></i></a>';
                }
                if (Gate::allows('delete.homework')) {
                    $html .= '<button class="btn btn-sm btn-danger delete-record" data-url="' . $deleteUrl . '"><i class="fas fa-trash"></i></button>';
                }
                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['action', 'files'])
            ->make(true);
    }

    public function create()
    {
        $subjects = Subject::where('status', 1)->get();
        $classes = StudentClass::where('status', 1)->get();

        return view('homework.form', compact('subjects', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:student_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'submission_date' => 'required|date|after_or_equal:date',
            'file_upload' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'study_material_upload' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $homework = new Homework();
        $homework->class_id = $request->class_id;
        $homework->subject_id = $request->subject_id;
        $homework->title = $request->title;
        $homework->description = $request->description;
        $homework->date = $request->date;
        $homework->submission_date = $request->submission_date;
        $homework->created_by = Auth::id();

        if ($request->hasFile('file_upload')) {
            $homework->file_upload = $request->file('file_upload')->store('homeworks', 'public');
        }

        if ($request->hasFile('study_material_upload')) {
            $homework->study_material_upload = $request->file('study_material_upload')->store('study_materials', 'public');
        }

        $homework->save();

        return redirect()->route('homework.index')->with('success', 'Homework assigned successfully.');
    }

    public function show($id)
    {
        $homework = Homework::with(['subject', 'class'])->findOrFail(decrypt($id));
        return view('homework.show', compact('homework'));
    }

    public function edit($id)
    {
        $homework = Homework::findOrFail(decrypt($id));
        $subjects = Subject::where('status', 1)->get();
        $classes = StudentClass::where('status', 1)->get();

        return view('homework.form', compact('homework', 'subjects', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $homework = Homework::findOrFail(decrypt($id));

        $request->validate([
            'class_id' => 'required|exists:student_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'submission_date' => 'required|date|after_or_equal:date',
            'file_upload' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'study_material_upload' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $homework->class_id = $request->class_id;
        $homework->subject_id = $request->subject_id;
        $homework->title = $request->title;
        $homework->description = $request->description;
        $homework->date = $request->date;
        $homework->submission_date = $request->submission_date;

        if ($request->hasFile('file_upload')) {
            if ($homework->file_upload) {
                Storage::disk('public')->delete($homework->file_upload);
            }
            $homework->file_upload = $request->file('file_upload')->store('homeworks', 'public');
        }

        if ($request->hasFile('study_material_upload')) {
            if ($homework->study_material_upload) {
                Storage::disk('public')->delete($homework->study_material_upload);
            }
            $homework->study_material_upload = $request->file('study_material_upload')->store('study_materials', 'public');
        }

        $homework->save();

        return redirect()->route('homework.index')->with('success', 'Homework updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $homework = Homework::findOrFail(decrypt($id));

        if ($homework->file_upload) {
            Storage::disk('public')->delete($homework->file_upload);
        }
        if ($homework->study_material_upload) {
            Storage::disk('public')->delete($homework->study_material_upload);
        }

        $homework->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Homework deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Homework deleted successfully.');
    }
}
