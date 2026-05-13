<?php

namespace App\Http\Controllers;

use App\Models\FeeCategory;
use App\Models\StudentFee;
use App\Models\FeePayment;
use App\Models\StudentAdmissionManagement;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class FeeCollectionController extends Controller
{
    protected $moduleName = 'Fee Collection Management';
    protected $moduleLink = '';

    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('fee.collection.dashboard'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:assign.fee')->only('assignFeeForm', 'storeFeeAssignment');
        $this->middleware('permission:collect.fee')->only('collectFeeForm', 'storeFeePayment');
        $this->middleware('permission:view.fee.dashboard')->only('dashboard', 'getDashboardData');
        $this->middleware('permission:view.fee.history')->only('studentHistory', 'generateReceipt');
    }

    public function dashboard()
    {
        return view('fee_collection.fee_dashboard');
    }

    public function getDashboardData(Request $request)
    {
        $data = User::whereHas('roles', function ($query) {
            $query->where('roles.id', User::ROLE_STUDENT)
                ->where('roles.id', '!=', User::SUPERADMIN_ROLE_ID);
        })->with('studentFees')->select('users.*');

        return Datatables::eloquent($data)
            ->addIndexColumn()
            ->addColumn('total_fees', function ($row) {
                return number_format($row->studentFees->sum('amount'), 2);
            })
            ->addColumn('paid_amount', function ($row) {
                return number_format($row->studentFees->sum('paid_amount'), 2);
            })
            ->addColumn('pending_amount', function ($row) {
                $pending = $row->studentFees->sum('amount') - $row->studentFees->sum('paid_amount');
                return number_format($pending, 2);
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                if (Gate::allows('view.fee.history')) {
                    $historyUrl = route('fee.collection.history', encrypt($row->id));
                    $btn .= '<a href="' . $historyUrl . '" class="btn btn-info btn-sm"><i class="fas fa-history"></i> History</a>';
                }
                if ($btn == '') {
                    $btn = '-';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function assignFeeForm()
    {
        $categories = FeeCategory::where('status', 1)->get();
        $students = User::whereHas('roles', function ($query) {
            $query->where('roles.id', User::ROLE_STUDENT)
                ->where('roles.id', '!=', User::SUPERADMIN_ROLE_ID);
        })->with('studentFees')->select('users.*')->get();
        return view('fee_collection.assign', compact('categories', 'students'));
    }

    public function storeAssignedFee(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'fee_category_id' => 'required|exists:fee_categories,id',
            'due_date' => 'nullable|date',
        ]);

        $category = FeeCategory::findOrFail($request->fee_category_id);

        StudentFee::create([
            'student_id' => $request->student_id,
            'fee_category_id' => $category->id,
            'amount' => $category->amount,
            'paid_amount' => 0,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Fee assigned successfully.');
    }

    public function collectFeeForm()
    {
        $studentFees = StudentFee::with(['student', 'category'])->whereIn('status', ['pending', 'partial'])->get();
        return view('fee_collection.collect', compact('studentFees'));
    }

    public function storeFeePayment(Request $request)
    {
        $request->validate([
            'student_fee_id' => 'required|exists:student_fees,id',
            'amount_paid' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        $studentFee = StudentFee::findOrFail($request->student_fee_id);

        $totalPaid = $studentFee->paid_amount + $request->amount_paid;

        if ($totalPaid > $studentFee->amount) {
            return redirect()->back()->withErrors(['amount_paid' => 'Payment amount exceeds total fee amount.']);
        }

        do {
            $receiptNumber = 'REC-' . strtoupper(Str::random(8));
        } while (FeePayment::where('receipt_number', $receiptNumber)->exists());

        $payment = FeePayment::create([
            'student_fee_id' => $studentFee->id,
            'amount_paid' => $request->amount_paid,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'receipt_number' => $receiptNumber,
        ]);

        $status = 'partial';
        if ($totalPaid == $studentFee->amount) {
            $status = 'paid';
        }

        $studentFee->update([
            'paid_amount' => $totalPaid,
            'status' => $status,
        ]);

        return redirect()->back()->with('success', 'Payment collected successfully. Receipt: ' . $receiptNumber);
    }

    public function studentHistory($student_id)
    {
        $student_id = decrypt($student_id);
        $student = User::findOrFail($student_id);
        $fees = StudentFee::with(['category', 'payments'])->where('student_id', $student_id)->get();

        return view('fee_collection.history', compact('student', 'fees'));
    }

    public function generateReceipt($receipt_number)
    {
        $payment = FeePayment::with(['studentFee.student', 'studentFee.category'])
            ->where('receipt_number', $receipt_number)
            ->firstOrFail();

        $pdf = Pdf::loadview('fee_collection.receipt', compact('payment'));

        return $pdf->download('receipt-' . $receipt_number . '.pdf');
    }
}
