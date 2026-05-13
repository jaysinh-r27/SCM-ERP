<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    protected $moduleName = 'User Management';
    protected $moduleLink = '';

    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('user.index'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:create.user')->only('create', 'store');
        $this->middleware('permission:edit.user')->only('edit', 'update');
        $this->middleware('permission:delete.user')->only('destroy');
        $this->middleware('permission:view.user')->only('index', 'show');
    }

    public function index()
    {
        $roles = Role::where('id', '!=', User::SUPERADMIN_ROLE_ID)->get();
        return view('user.index', compact('roles'));
    }

    public function getData(Request $request)
    {
        $data = User::where('id', '!=', Auth::id())
            ->whereHas('roles', function ($query) use ($request) {
                $query->where('roles.id', '!=', User::SUPERADMIN_ROLE_ID)
                    ->when(!empty($request->role_id), function ($query) use ($request) {
                        $query->where('roles.id', $request->role_id);
                    });
            });

        return DataTables::eloquent($data)
            ->addIndexColumn()
            ->editColumn('email', function ($row) {
                return $row->email ?? '-';
            })
            ->editColumn('phone', function ($row) {
                return $row->phone ?? '-';
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge badge-success p-2">Active</span>';
                } else {
                    return '<span class="badge badge-danger p-2">Inactive</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $viewUrl = route('user.show', encrypt($row->id));
                $editUrl = route('user.edit', encrypt($row->id));
                $deleteUrl = route('user.destroy', encrypt($row->id));

                $html = '<div class="btn-group">';
                if (Gate::allows('view.user')) {
                    $html .= '<a href="' . $viewUrl . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                }
                if (Gate::allows('edit.user')) {
                    $html .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary ml-1 mr-1"><i class="fas fa-edit"></i></a>';
                }
                if (Gate::allows('delete.user')) {
                    $html .= '<button class="btn btn-sm btn-danger delete-record" data-url="' . $deleteUrl . '"><i class="fas fa-trash"></i></button>';
                }
                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['action', 'status', 'email', 'phone'])
            ->make(true);
    }

    public function create()
    {
        $roles = Role::all();
        return view('user.form', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:12',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:0,1',
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
            ['role_id' => $request->role_id]
        );

        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        $user = User::findOrFail(decrypt($id));
        $roleId = RoleUser::where('user_id', $user->id)->value('role_id');
        $role = Role::find($roleId);
        return view('user.show', compact('user', 'role'));
    }

    public function edit($id)
    {
        $user = User::findOrFail(decrypt($id));
        $roles = Role::all();
        $userRole = RoleUser::where('user_id', $user->id)->value('role_id');
        return view('user.form', compact('user', 'roles', 'userRole'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail(decrypt($id));

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:12',
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:0,1',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->status = $request->status;
        $user->save();

        RoleUser::updateOrCreate(
            ['user_id' => $user->id],
            ['role_id' => $request->role_id]
        );

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail(decrypt($id));
        $user->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'User deleted successfully.']);
        }

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
