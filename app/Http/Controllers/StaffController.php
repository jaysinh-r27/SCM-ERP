<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\StaffProfile;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{
    protected $moduleName = 'Staff Management';
    protected $moduleLink = '';

    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('staff.index'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:create.staff')->only('create', 'store', 'checkEmail');
        $this->middleware('permission:edit.staff')->only('edit', 'update', 'checkEmail');
        $this->middleware('permission:delete.staff')->only('destroy');
        $this->middleware('permission:view.staff')->only('index', 'show', 'getData');
    }

    public function index()
    {
        return view('staff.index');
    }

    public function getData()
    {
        $data = StaffProfile::with(['subject']);

        return DataTables::eloquent($data)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                return $row->name ?? '-';
            })
            ->editColumn('email', function ($row) {
                return $row->email ?? '-';
            })
            ->editColumn('phone', function ($row) {
                return $row->phone ?? '-';
            })
            ->addColumn('subject_id', function ($row) {
                return ($row->subject && $row->subject) ? $row->subject->name : '-';
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge badge-success p-2">Active</span>';
                } else {
                    return '<span class="badge badge-danger p-2">Inactive</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $viewUrl = route('staff.show', encrypt($row->id));
                $editUrl = route('staff.edit', encrypt($row->id));
                $deleteUrl = route('staff.destroy', encrypt($row->id));

                $html = '<div class="btn-group">';
                if (Gate::allows('view.staff')) {
                    $html .= '<a href="' . $viewUrl . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                }
                if (Gate::allows('edit.staff')) {
                    $html .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary ml-1 mr-1"><i class="fas fa-edit"></i></a>';
                }
                if (Gate::allows('delete.staff')) {
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
        $subjects = Subject::where('status', 1)->get();
        return view('staff.form', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:staff_profiles,email',
            'phone' => 'nullable|string|max:12',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|in:0,1',
            'subject_id' => 'required|exists:subjects,id',
            'qualification' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'basic_salary' => 'nullable|numeric|min:0',
            'account_number' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:20',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->status = $request->status;
        $user->save();

        RoleUser::updateOrCreate(
            ['user_id' => $user->id],
            ['role_id' => User::ROLE_STAFF]
        );

        StaffProfile::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject_id' => $request->subject_id,
            'qualification' => $request->qualification,
            'joining_date' => $request->joining_date,
            'basic_salary' => $request->basic_salary ?? 0,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'ifsc_code' => $request->ifsc_code,
            'status' => $request->status,
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully.');
    }

    public function show($id)
    {
        $staff = StaffProfile::with(['subject'])->findOrFail(decrypt($id));
        return view('staff.show', compact('staff'));
    }

    public function edit($id)
    {
        $staff = StaffProfile::with(['subject'])->findOrFail(decrypt($id));
        $subjects = Subject::where('status', 1)->get();
        return view('staff.form', compact('staff', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        $staff = StaffProfile::findOrFail(decrypt($id));

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:staff_profiles,email,' . $staff->id,
            'phone' => 'nullable|string|max:12',
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:0,1',
            'subject_id' => 'required|exists:subjects,id',
            'qualification' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'basic_salary' => 'nullable|numeric|min:0',
            'account_number' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:20',
        ]);

        $staff->update(
            [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject_id' => $request->subject_id,
                'qualification' => $request->qualification,
                'joining_date' => $request->joining_date,
                'basic_salary' => $request->basic_salary ?? 0,
                'account_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'ifsc_code' => $request->ifsc_code,
                'status' => $request->status,
            ]
        );

        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $staff = StaffProfile::findOrFail(decrypt($id));

        if (!empty($staff->user_id)) {
            $user = User::where('id', $staff->user_id)->first();
            if (!empty($user)) {
                $user->delete();
            }
        }
        $staff->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Staff member deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Staff member deleted successfully.');
    }

    public function checkEmail(Request $request)
    {
        $email = $request->email;
        $id = $request->id ? decrypt($request->id) : null;

        $exists = StaffProfile::where('email', $email)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(!$exists);
    }
}
