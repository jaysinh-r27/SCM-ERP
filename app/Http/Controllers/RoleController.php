<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    protected $moduleName = 'Role Management';
    protected $moduleLink = '';
    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('role.index'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:create.role')->only('create', 'store');
        $this->middleware('permission:edit.role')->only('edit', 'update');
        $this->middleware('permission:delete.role')->only('destroy');
        $this->middleware('permission:view.role')->only('index', 'show');
    }

    public function index()
    {

        return view('role.index');
    }

    public function getData()
    {
        $data = Role::select('roles.*');

        return DataTables::eloquent($data)
            ->addIndexColumn()
            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge badge-success p-2">Active</span>';
                } else {
                    return '<span class="badge badge-danger p-2">Inactive</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $viewUrl = route('role.show', encrypt($row->id));
                $editUrl = route('role.edit', encrypt($row->id));
                $deleteUrl = route('role.destroy', encrypt($row->id));

                $html = '<div class="btn-group">';
                if (Gate::allows('view.role')) {
                    $html .= '<a href="' . $viewUrl . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                }
                if (Gate::allows('edit.role')) {
                    $html .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary ml-1 mr-1"><i class="fas fa-edit"></i></a>';
                }
                if (Gate::allows('delete.role')) {
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
        $permissions = Permission::where('status', 1)->get()->groupBy('module');
        return view('role.form', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);

        $role = new Role();
        $role->name = $request->name;
        $role->slug = Str::slug($request->name);
        $role->description = $request->description;
        $role->status = $request->status;
        $role->save();

        if (!empty($request->permissions) && !empty($role->id)) {
            foreach ($request->permissions as $permissionId) {
                $rolePermission = new RolePermission();
                $rolePermission->role_id = $role->id;
                $rolePermission->permission_id = $permissionId;
                $rolePermission->save();
            }
        }

        return redirect()->route('role.index')->with('success', 'Role created successfully.');
    }

    public function show($id)
    {
        $role = Role::with(['permissions'])->findOrFail(decrypt($id));
        $permissions = Permission::where('status', 1)->get()->groupBy('module');
        return view('role.show', compact('role', 'permissions'));
    }

    public function edit($id)
    {
        $role = Role::with(['permissions'])->findOrFail(decrypt($id));
        $permissions = Permission::where('status', 1)->get()->groupBy('module');
        return view('role.form', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail(decrypt($id));

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);

        $role->name = $request->name;
        $role->slug = Str::slug($request->name);
        $role->description = $request->description;
        $role->status = $request->status;
        $role->update();

        RolePermission::where('role_id', $role->id)->delete();
        if (!empty($request->permissions) && !empty($role->id)) {
            foreach ($request->permissions as $permissionId) {
                $rolePermission = new RolePermission();
                $rolePermission->role_id = $role->id;
                $rolePermission->permission_id = $permissionId;
                $rolePermission->save();
            }
        }

        return redirect()->route('role.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $role = Role::findOrFail(decrypt($id));
        $role->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Role deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Role deleted successfully.');
    }

    public function checkName(Request $request)
    {
        $name = $request->input('name');
        $roleId = $request->input('role_id');

        $query = Role::where('name', $name);
        if ($roleId) {
            $query->where('id', '!=', $roleId);
        }

        if ($query->exists()) {
            return response()->json(false);
        }

        return response()->json(true);
    }
}
