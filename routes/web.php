<?php

use App\Http\Controllers\AcademicSessionController;
use App\Http\Controllers\ClassManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamResultController;
use App\Http\Controllers\FeeCategoryController;
use App\Http\Controllers\FeeCollectionController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StaffAttendanceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentAdmissionManagementController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('student-admission-management/get-data', [StudentAdmissionManagementController::class, 'getData'])->name('student.admission.management.getData');
    Route::resource('student-admission-management', StudentAdmissionManagementController::class)->names('student.admission.management');

    Route::get('section/get-data', [SectionController::class, 'getData'])->name('section.getData');
    Route::resource('section', SectionController::class)->names('section');

    Route::get('subject/get-data', [SubjectController::class, 'getData'])->name('subject.getData');
    Route::resource('subject', SubjectController::class)->names('subject');

    Route::get('student-class/get-data', [ClassManagementController::class, 'getData'])->name('student.class.getData');
    Route::resource('student-class', ClassManagementController::class)->names('student.class');

    Route::get('academic-session/get-data', [AcademicSessionController::class, 'getData'])->name('academic.session.getData');
    Route::resource('academic-session', AcademicSessionController::class)->names('academic.session');

    Route::get('user/get-data', [UserController::class, 'getData'])->name('user.getData');
    Route::resource('user', UserController::class)->names('user');

    Route::get('role/get-data', [RoleController::class, 'getData'])->name('role.getData');
    Route::get('role/check-name', [RoleController::class, 'checkName'])->name('role.checkName');
    Route::resource('role', RoleController::class)->names('role');

    Route::get('student-attendance-monthly-report', [StudentAttendanceController::class, 'monthlyReport'])->name('student.attendance.monthly.report');
    Route::get('student-attendance-list', [StudentAttendanceController::class, 'list'])->name('student.attendance.list');
    Route::resource('student-attendance', StudentAttendanceController::class)->names('student.attendance');

    Route::get('staff-attendance-list', [StaffAttendanceController::class, 'list'])->name('staff.attendance.list');
    Route::resource('staff-attendance', StaffAttendanceController::class)->names('staff.attendance');

    Route::get('fee-category/get-data', [FeeCategoryController::class, 'getData'])->name('fee-category.getData');
    Route::resource('fee-category', FeeCategoryController::class);

    Route::prefix('fee-collection')->name('fee.collection.')->group(function () {
        Route::get('payment-history', [FeeCollectionController::class, 'dashboard'])->name('dashboard');
        Route::get('payment-history/data', [FeeCollectionController::class, 'getDashboardData'])->name('dashboard.data');
        Route::get('assign', [FeeCollectionController::class, 'assignFeeForm'])->name('assign');
        Route::post('assign', [FeeCollectionController::class, 'storeAssignedFee'])->name('store.assign');
        Route::get('collect', [FeeCollectionController::class, 'collectFeeForm'])->name('collect');
        Route::post('collect', [FeeCollectionController::class, 'storeFeePayment'])->name('store.collect');
        Route::get('history/{student_id}', [FeeCollectionController::class, 'studentHistory'])->name('history');
        Route::get('receipt/{receipt_number}', [FeeCollectionController::class, 'generateReceipt'])->name('receipt');
    });

    Route::get('exam/get-data', [ExamController::class, 'getData'])->name('exam.getData');
    Route::resource('exam', ExamController::class)->names('exam');

    Route::prefix('exam-result')->name('exam-result.')->group(function () {
        Route::get('/', [ExamResultController::class, 'index'])->name('index');
        Route::get('get-data', [ExamResultController::class, 'getData'])->name('getData');
        Route::get('marks-entry', [ExamResultController::class, 'marksEntryForm'])->name('marks-entry');
        Route::post('store-marks', [ExamResultController::class, 'storeMarks'])->name('store-marks');
        Route::get('report-card/{id}', [ExamResultController::class, 'generateReportCard'])->name('report-card');
    });

    Route::get('homework/get-data', [HomeworkController::class, 'getData'])->name('homework.getData');
    Route::resource('homework', HomeworkController::class)->names('homework');

    Route::get('staff/get-data', [StaffController::class, 'getData'])->name('staff.getData');
    Route::get('staff/check-email', [StaffController::class, 'checkEmail'])->name('staff.checkEmail');
    Route::resource('staff', StaffController::class)->names('staff');

    Route::prefix('report')->name('report.')->group(function () {
        Route::get('student-list-report', [ReportController::class, 'studentListReport'])->name('student.list');
        Route::get('exam-result-report', [ReportController::class, 'examResultReport'])->name('exam.result');
        Route::get('fee-report', [ReportController::class, 'feeReport'])->name('fee');
        Route::get('attendance-report', [ReportController::class, 'attendanceReport'])->name('attendance');
    });
});

require __DIR__ . '/auth.php';
