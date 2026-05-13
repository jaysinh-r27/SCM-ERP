<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AcademicSessionController extends Controller
{
    protected $moduleName = 'Academic Session Management';
    protected $moduleLink = '';
    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('academic.session.index'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:create.academic.session')->only('create', 'store');
        $this->middleware('permission:edit.academic.session')->only('edit', 'update');
        $this->middleware('permission:delete.academic.session')->only('destroy');
        $this->middleware('permission:view.academic.session')->only('index', 'show');
    }

    public function index()
    {
        return view('academic_session.index');
    }

    public function getData()
    {
        $data = AcademicSession::select('academic_sessions.*');

        return \Yajra\DataTables\Facades\DataTables::eloquent($data)
            ->addIndexColumn()
            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge badge-success p-2">Active</span>';
                } else {
                    return '<span class="badge badge-danger p-2">Inactive</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $viewUrl = route('academic.session.show', encrypt($row->id));
                $editUrl = route('academic.session.edit', encrypt($row->id));
                $deleteUrl = route('academic.session.destroy', encrypt($row->id));

                $html = '<div class="btn-group">';
                if (Gate::allows('view.academic.session')) {
                    $html .= '<a href="' . $viewUrl . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                }
                if (Gate::allows('edit.academic.session')) {
                    $html .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary ml-1 mr-1"><i class="fas fa-edit"></i></a>';
                }
                if (Gate::allows('delete.academic.session')) {
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
        return view('academic_session.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:0,1',
        ]);

        $session = new AcademicSession();
        $session->name = $request->name;
        $session->start_date = $request->start_date;
        $session->end_date = $request->end_date;
        $session->status = $request->status;
        $session->save();

        return redirect()->route('academic.session.index')->with('success', 'Academic Session created successfully.');
    }

    public function show($id)
    {
        $academicSession = AcademicSession::findOrFail(decrypt($id));
        return view('academic_session.show', compact('academicSession'));
    }

    public function edit($id)
    {
        $academicSession = AcademicSession::findOrFail(decrypt($id));
        return view('academic_session.form', compact('academicSession'));
    }

    public function update(Request $request, $id)
    {
        $academicSession = AcademicSession::findOrFail(decrypt($id));

        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:0,1',
        ]);

        $academicSession->name = $request->name;
        $academicSession->start_date = $request->start_date;
        $academicSession->end_date = $request->end_date;
        $academicSession->status = $request->status;
        $academicSession->save();

        return redirect()->route('academic.session.index')->with('success', 'Academic Session updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $academicSession = AcademicSession::findOrFail(decrypt($id));
        $academicSession->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Academic Session deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Academic Session deleted successfully.');
    }
}
