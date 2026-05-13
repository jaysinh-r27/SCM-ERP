<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\RoleUser;
use App\Models\StudentAdmissionManagement;
use App\Models\StudentClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class StudentAdmissionManagementController extends Controller
{
    protected $moduleName = 'Student Admission Management';
    protected $moduleLink = '';
    protected $authUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authUser = Auth::user();

            view()->share([
                'moduleName' => $this->moduleName,
                'moduleLink' => route('student.admission.management.index'),
            ]);

            return $next($request);
        });

        $this->middleware('permission:create.student.admission')->only('create', 'store');
        $this->middleware('permission:edit.student.admission')->only('edit', 'update');
        $this->middleware('permission:delete.student.admission')->only('destroy');
        $this->middleware('permission:view.student.admission')->only('index', 'show');
    }

    public function index()
    {
        return view('student-admission-management.index');
    }

    public function getData()
    {
        $data = StudentAdmissionManagement::with(['studentClasses']);

        return DataTables::eloquent($data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {

                $image = $row->profile_image
                    ? asset('storage/' . $row->profile_image)
                    : asset('assets/img/user2-160x160.jpg');

                return '
                <div class="d-flex align-items-center">
                    
                    <img src="' . $image . '" 
                        width="45" 
                        height="45"
                        class="rounded-circle mr-2"
                        style="object-fit:cover; border:1px solid #ddd;"
                    >

                    <div>
                        <div class="font-weight-bold">
                            ' . $row->name . '

                            <i class="fas fa-info-circle text-primary ml-1"
                               data-toggle="tooltip"
                               title="' . $row->address . '">
                            </i>
                        </div>

                        <small class="text-muted">
                            Admission No : <span class="font-weight-bold text-dark"> ' . $row->admission_no . '</span>
                        </small>
                    </div>

                </div>
            ';
            })
            ->editColumn('email', function ($row) {
                return !empty($row->email) ? $row->email : '-';
            })
            ->editColumn('mobile', function ($row) {
                return !empty($row->mobile) ? $row->mobile : '-';
            })
            ->editColumn('class_id', function ($row) {
                return !empty($row->studentClasses->name) ? $row->studentClasses->name : '-';
            })
            ->addColumn('parent_details', function ($row) {
                return '
                <div>
                    <div>
                        <strong>Father :</strong>
                        ' . $row->father_name . '
                    </div>

                    <div>
                        <strong>Mother :</strong>
                        ' . $row->mother_name . '
                    </div>
                </div>
            ';
            })
            ->editColumn('admission_status', function ($row) {

                $badgeClass = match ($row->admission_status) {
                    'pending' => 'badge-warning',
                    'submitted' => 'badge-info',
                    'approved' => 'badge-success',
                    'rejected' => 'badge-danger',
                    'fee_pending' => 'badge-primary',
                    'on_hold' => 'badge-secondary',
                    'cancelled' => 'badge-dark',
                    default => 'badge-light'
                };

                return '
                <span class="badge ' . $badgeClass . ' p-2">
                    ' . ucwords(str_replace('_', ' ', $row->admission_status)) . '
                </span>
            ';
            })
            ->editColumn('admission_date', function ($row) {
                return $row->admission_date
                    ? Carbon::parse($row->admission_date)->format('d M Y')
                    : '-';
            })
            ->addColumn('action', function ($row) {

                $viewUrl = route('student.admission.management.show', encrypt($row->id));
                $editUrl = route('student.admission.management.edit', encrypt($row->id));
                $deleteUrl = route('student.admission.management.destroy', encrypt($row->id));

                $html = '<div class="btn-group">';
                if (Gate::allows('view.student.admission')) {
                    $html .= '<a href="' . $viewUrl . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                }
                if (Gate::allows('edit.student.admission')) {
                    $html .= '<a href="' . $editUrl . '" class="btn btn-sm btn-primary ml-1 mr-1"><i class="fas fa-edit"></i></a>';
                }
                if (Gate::allows('delete.student.admission')) {
                    $html .= '<button class="btn btn-sm btn-danger delete-record" data-url="' . $deleteUrl . '"><i class="fas fa-trash"></i></button>';
                }
                $html .= '</div>';

                return $html;
            })

            ->rawColumns([
                'name',
                'email',
                'mobile',
                'admission_date',
                'parent_details',
                'admission_status',
                'action'
            ])

            ->make(true);
    }

    public function create()
    {
        $classes = StudentClass::where('status', 1)->get();

        $lastRecord = StudentAdmissionManagement::withTrashed()->orderBy('id', 'desc')->first();

        if (! $lastRecord || empty($lastRecord->admission_no)) {
            $lastadmission_no = 'SA-0001';
        } else {
            $lastNumber = (int) substr($lastRecord->admission_no, 3);

            $newNumber = $lastNumber + 1;

            $lastadmission_no = 'SA-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        $academic_sessions = AcademicSession::where('status', 1)->get();

        return view('student-admission-management.form', compact('classes', 'lastadmission_no', 'academic_sessions'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name'             => 'required|string|max:255',
            'admission_no'     => 'required|string|unique:student_admission_management,admission_no',
            'admission_date'   => 'required|date',
            'mobile'           => 'required|numeric|max_digits:12',
            'email'            => 'required|email|max:255',
            'class_id'         => 'required|integer',
            'address'          => 'required|string',
            'father_name'      => 'required|string|max:255',
            'mother_name'      => 'required|string|max:255',
            'admission_status' => 'required|string',
            'profile_image'    => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'documents'        => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'academic_session_id' => 'required',
        ]);

        $profileImagePath = null;

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');

            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $profileImagePath = $file->storeAs('student_profile_image', $fileName, 'public');
        }

        $documentPath = null;
        if ($request->hasFile('documents')) {
            $file = $request->file('documents');
            $fileName = time() . '_doc.' . $file->getClientOriginalExtension();
            $documentPath = $file->storeAs('student_documents', $fileName, 'public');
        }

        $student = new StudentAdmissionManagement();

        $student->name             = $request->name;
        $student->admission_no     = $request->admission_no;
        $student->mobile           = $request->mobile;
        $student->email            = $request->email;
        $student->class_id         = $request->class_id;
        $student->admission_date   = $request->admission_date;
        $student->admission_status = $request->admission_status;
        $student->father_name      = $request->father_name;
        $student->mother_name      = $request->mother_name;
        $student->address          = $request->address;
        $student->profile_image    = $profileImagePath;
        $student->documents        = $documentPath;
        $student->academic_session_id = $request->academic_session_id;
        $student->save();

        if (!empty($student->id) && $student->admission_status == 'approved') {
            $user = User::where('phone', $student->mobile)->exists();
            if (!$user) {
                $user = User::create([
                    'name' => $student->name,
                    'email' => $student->email,
                    'phone' => $student->mobile,
                    'password' => Hash::make($student->mobile),
                    'status' => 1
                ]);

                RoleUser::create([
                    'user_id' => $user->id,
                    'role_id' => 4,
                ]);

                StudentAdmissionManagement::where('id', $student->id)->update([
                    'user_id' => $user->id
                ]);
            }
        }

        return redirect()->route('student.admission.management.index')->with('success', 'Student Admission Management created successfully.');
    }

    public function show(Request $request, $student_admission_management)
    {
        $classes = StudentClass::where('status', 1)->get();
        $student = StudentAdmissionManagement::where('id', decrypt($student_admission_management))->firstOrFail();
        $academic_sessions = AcademicSession::where('status', 1)->get();

        return view('student-admission-management.show', compact('classes', 'student'));
    }

    public function edit(Request $request, $student_admission_management)
    {
        $classes = StudentClass::where('status', 1)->get();
        $student = StudentAdmissionManagement::where('id', decrypt($student_admission_management))->firstOrFail();
        $academic_sessions = AcademicSession::where('status', 1)->get();

        return view('student-admission-management.form', compact('classes', 'student', 'academic_sessions'));
    }

    public function update(Request $request, $student_admission_management)
    {
        $student = StudentAdmissionManagement::where('id', decrypt($student_admission_management))->firstOrFail();

        $request->validate([
            'name'             => 'required|string|max:255',
            'admission_no'     => 'required|string|unique:student_admission_management,admission_no,' . $student->id,
            'admission_date'   => 'required|date',
            'mobile'           => 'required|numeric|max_digits:12',
            'email'            => 'required|email|max:255',
            'class_id'         => 'required|integer',
            'address'          => 'required|string',
            'father_name'      => 'required|string|max:255',
            'mother_name'      => 'required|string|max:255',
            'admission_status' => 'required|string',
            'profile_image'    => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'documents'        => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'academic_session_id' => 'required',
        ]);

        $profileImagePath = $student->profile_image;

        if ($request->input('remove_profile_image') == '1') {
            if ($profileImagePath && Storage::disk('public')->exists($profileImagePath)) {
                Storage::disk('public')->delete($profileImagePath);
            }
            $profileImagePath = null;
        }

        if ($request->hasFile('profile_image')) {
            if ($profileImagePath && Storage::disk('public')->exists($profileImagePath)) {
                Storage::disk('public')->delete($profileImagePath);
            }

            $file = $request->file('profile_image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $profileImagePath = $file->storeAs('student_profile_image', $fileName, 'public');
        }

        if ($request->input('remove_document') == '1') {
            if ($student->documents && Storage::disk('public')->exists($student->documents)) {
                Storage::disk('public')->delete($student->documents);
            }
            $student->documents = null;
        }

        if ($request->hasFile('documents')) {
            if ($student->documents && Storage::disk('public')->exists($student->documents)) {
                Storage::disk('public')->delete($student->documents);
            }
            $file = $request->file('documents');
            $fileName = time() . '_doc.' . $file->getClientOriginalExtension();
            $student->documents = $file->storeAs('student_documents', $fileName, 'public');
        }

        $student->name             = $request->name;
        $student->admission_no     = $request->admission_no;
        $student->mobile           = $request->mobile;
        $student->email            = $request->email;
        $student->class_id         = $request->class_id;
        $student->admission_date   = $request->admission_date;
        $student->admission_status = $request->admission_status;
        $student->father_name      = $request->father_name;
        $student->mother_name      = $request->mother_name;
        $student->address          = $request->address;
        $student->profile_image    = $profileImagePath;
        $student->academic_session_id = $request->academic_session_id;

        $student->update();

        if (!empty($student->id) && $student->admission_status == 'approved') {
            $user = User::where('id', $student->user_id)->exists();
            if (!$user) {
                $user = User::create([
                    'name' => $student->name,
                    'email' => $student->email,
                    'phone' => $student->mobile,
                    'password' => Hash::make($student->mobile),
                    'status' => 1
                ]);

                RoleUser::create([
                    'user_id' => $user->id,
                    'role_id' => 4,
                ]);

                StudentAdmissionManagement::where('id', $student->id)->update([
                    'user_id' => $user->id
                ]);
            }
        }

        return redirect()->route('student.admission.management.index')->with('success', 'Student Admission Management updated successfully.');
    }

    public function destroy(Request $request, $student_admission_management)
    {
        $student = StudentAdmissionManagement::where('id', decrypt($student_admission_management))->firstOrFail();

        if (!empty($student->id) && !empty($student->user_id)) {
            $user = User::where('id', $student->user_id)->first();
            if (!empty($user->id)) {
                $user->delete();
            }
        }

        $student->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Student Admission Management deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Student Admission Management deleted successfully.');
    }
}
