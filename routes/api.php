<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/student-list', [CommonController::class, 'getStudentList'])->middleware('permission:view.student.admission');
    Route::post('/add-homework', [CommonController::class, 'addHomework'])->middleware('permission:create.homework');
    Route::post('/get-homework', [CommonController::class, 'getHomework'])->middleware('permission:view.homework');
    Route::post('/get-fee-details', [CommonController::class, 'getFeeDetails'])->middleware('permission:view.fee.history');
});
