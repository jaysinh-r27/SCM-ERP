<?php

namespace App\Http\Controllers;

use App\Models\StudentFee;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $moduleName = 'Dashboard';
    protected $moduleLink = '';

    public function __construct()
    {
        view()->share([
            'moduleName' => $this->moduleName,
            'moduleLink' => route('dashboard'),
        ]);
    }

    public function index()
    {
        $baseQuery = User::query();

        $student_count = (clone $baseQuery)->whereHas('roles', function ($q) {
            $q->where('roles.id', User::ROLE_STUDENT);
        })->count();

        $staff_count = (clone $baseQuery)->whereHas('roles', function ($q) {
            $q->whereNotIn('roles.id', [User::SUPERADMIN_ROLE_ID, User::ROLE_STUDENT]);
        })->count();

        $pending_fee_amount = StudentFee::sum('amount') - StudentFee::sum('paid_amount');

        return view('dashboard', compact('student_count', 'staff_count', 'pending_fee_amount'));
    }
}
