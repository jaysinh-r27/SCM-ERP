<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentFee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
    public function getStudentList(Request $request)
    {
        try {
            $students = User::select(['id', 'name', 'email', 'phone', 'status'])
                ->with([
                    'studentAdmission:id,user_id,class_id,admission_date',
                    'studentAdmission.studentClasses:id,name'
                ])
                ->whereHas('roles', function ($q) {
                    $q->where('roles.id', User::ROLE_STUDENT);
                })
                ->where('status', 1)
                ->get()
                ->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'phone' => $student->phone,
                        'class' => $student?->studentAdmission?->studentClasses?->name ?? '-',
                        'status' => $student->status == 1 ? 1 : 0
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Student list fetched successfully',
                'data' => $students
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Somthing went wrong!',
                'data' => []
            ]);
        }
    }

    public function addHomework(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:student_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'submission_date' => 'required|date|after_or_equal:date',
            'file_upload' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
            'study_material_upload' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => $validator->errors()
            ]);
        }

        try {

            $date = Carbon::parse($request->date)->format('Y-m-d');
            $submissionDate = Carbon::parse($request->submission_date)->format('Y-m-d');

            $homework = new Homework();
            $homework->class_id = $request->class_id;
            $homework->subject_id = $request->subject_id;
            $homework->title = $request->title;
            $homework->description = $request->description;
            $homework->date = $date;
            $homework->submission_date = $submissionDate;
            $homework->created_by = $request->user()->id;

            if ($request->hasFile('file_upload')) {
                $homework->file_upload = $request->file('file_upload')->store('homeworks', 'public');
            }

            if ($request->hasFile('study_material_upload')) {
                $homework->study_material_upload = $request->file('study_material_upload')->store('study_materials', 'public');
            }

            $homework->save();

            return response()->json([
                'status' => true,
                'message' => 'Homework added successfully',
                'data' => [
                    'id' => $homework->id,
                    'class_id' => $homework->class_id,
                    'subject_id' => $homework->subject_id,
                    'title' => $homework->title,
                    'description' => $homework->description,
                    'date' => date('d-m-Y', strtotime($homework->date)),
                    'submission_date' => date('d-m-Y', strtotime($homework->submission_date)),
                    'file_upload' => $homework->file_upload,
                    'study_material_upload' => $homework->study_material_upload,
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'data' => [],
                'error' => $th->getMessage()
            ]);
        }
    }

    public function getHomework(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:student_classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => $validator->errors()
            ]);
        }

        try {

            $homework = Homework::with(['class', 'subject'])
                ->where('class_id', $request->class_id);

            if ($request->subject_id) {
                $homework->where('subject_id', $request->subject_id);
            }

            $homeworks = $homework->latest()->get();

            if ($homeworks->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No homework found',
                    'data' => []
                ]);
            }

            $data = $homeworks->map(function ($homework) {

                return [
                    'id' => $homework->id,

                    'class_id' => $homework->class_id,
                    'class_name' => $homework->class?->name,

                    'subject_id' => $homework->subject_id,
                    'subject_name' => $homework->subject?->name,

                    'title' => $homework->title,
                    'description' => $homework->description,

                    'date' => $homework->date
                        ? Carbon::parse($homework->date)->format('d-m-Y')
                        : null,

                    'submission_date' => $homework->submission_date
                        ? Carbon::parse($homework->submission_date)->format('d-m-Y')
                        : null,

                    'file_upload' => $homework->file_upload
                        ? asset('storage/' . $homework->file_upload)
                        : null,

                    'study_material_upload' => $homework->study_material_upload
                        ? asset('storage/' . $homework->study_material_upload)
                        : null,
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Homework list fetched successfully',
                'data' => $data
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'data' => [],
                'error' => $th->getMessage()
            ]);
        }
    }

    public function getFeeDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => $validator->errors()
            ]);
        }

        $fees = StudentFee::with(['category', 'payments', 'student'])
            ->where('student_id', $request->student_id)
            ->get()
            ->map(function ($fee) {
                $remainingAmount = $fee->amount - $fee->amount_paid;
                return [
                    'id' => $fee->id,
                    'student_id' => $fee->student_id,
                    'student_name' => $fee?->student?->name ?? '-',
                    'fee_category' => $fee?->category?->name ?? '-',
                    'total_fee' => number_format($fee->amount, 2),
                    'amount_paid' => number_format($fee->amount_paid, 2),
                    'remaining_amount' => number_format($remainingAmount, 2),
                    'due_date' => Carbon::parse($fee->due_date)->format('d-m-Y'),
                    'status' => ucfirst($fee->status),
                    'payment_details' => $fee->payments->map(function ($payment) {
                        return [
                            'id' => $payment->id,
                            'receipt_number' => $payment->receipt_number,
                            'amount_paid' => number_format($payment->amount_paid, 2),
                            'payment_date' => Carbon::parse($payment->payment_date)->format('d-m-Y'),
                            'payment_method' => ucfirst($payment->payment_method),
                        ];
                    })
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'Fee details fetched successfully',
            'data' => $fees
        ]);
    }
}
