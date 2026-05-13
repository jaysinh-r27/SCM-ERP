<?php

namespace App\Http\Controllers;

use App\Models\FeeCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class FeeCategoryController extends Controller
{
    protected $moduleName = 'Fee Category Management';
    protected $moduleLink = '';

    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('fee-category.index'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:create.fee.category')->only('create', 'store');
        $this->middleware('permission:edit.fee.category')->only('edit', 'update');
        $this->middleware('permission:delete.fee.category')->only('destroy');
        $this->middleware('permission:view.fee.category')->only('index', 'getData');
    }

    public function index()
    {
        return view('fee_category.index');
    }

    public function getData(Request $request)
    {
        $data = FeeCategory::select('fee_categories.*');
        return Datatables::eloquent($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                if (\Illuminate\Support\Facades\Gate::allows('edit.fee.category')) {
                    $editUrl = route('fee-category.edit', encrypt($row->id));
                    $btn .= '<a href="' . $editUrl . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
                }
                if (\Illuminate\Support\Facades\Gate::allows('delete.fee.category')) {
                    $deleteUrl = route('fee-category.destroy', encrypt($row->id));
                    $btn .= ' <button type="button" onclick="deleteRecord(\'' . $deleteUrl . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
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
        return view('fee_category.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
        ]);

        FeeCategory::create($request->all());

        return redirect()->route('fee-category.index')->with('success', 'Fee Category created successfully.');
    }

    public function edit($id)
    {
        $category = FeeCategory::findOrFail(decrypt($id));
        return view('fee_category.form', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
        ]);

        $category = FeeCategory::findOrFail(decrypt($id));
        $category->update($request->all());

        return redirect()->route('fee-category.index')->with('success', 'Fee Category updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $category = FeeCategory::findOrFail(decrypt($id));
        $category->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Fee Category deleted successfully.']);
        }
        return redirect()->back()->with('success', 'Fee Category deleted successfully.');
    }
}
