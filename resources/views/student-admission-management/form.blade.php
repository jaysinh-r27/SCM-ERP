<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12">

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ isset($student) ? 'Edit' : 'Create' }}</h3>
                        </div>

                        <form
                            action="{{ isset($student) ? route('student.admission.management.update', encrypt($student->id)) : route('student.admission.management.store') }}"
                            method="POST" enctype="multipart/form-data" id="SAForm">
                            @csrf
                            @if (isset($student))
                                @method('PUT')
                            @endif
                            <input type="hidden" id="student_id" name="student_id"
                                value="{{ isset($student) ? encrypt($student->id) : '' }}">
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-ban"></i> Please fix the following errors:</h5>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Student Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                id="name" placeholder="Enter student name"
                                                value="{{ old('name', $student->name ?? '') }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="admission_no">Admission Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('admission_no') is-invalid @enderror"
                                                name="admission_no" id="admission_no"
                                                placeholder="Enter admission number"
                                                value="{{ old('admission_no', $student->admission_no ?? ($lastadmission_no ?? '')) }}"
                                                readonly>
                                            @error('admission_no')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mobile">Mobile Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('mobile') is-invalid @enderror"
                                                name="mobile" id="mobile" placeholder="Enter mobile number"
                                                value="{{ old('mobile', $student->mobile ?? '') }}">
                                            @error('mobile')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email Address <span
                                                    class="text-danger">*</span></label>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                id="email" placeholder="Enter email address"
                                                value="{{ old('email', $student->email ?? '') }}">
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="academic_session_id">Academic Session <span
                                                    class="text-danger">*</span></label>
                                            <select
                                                class="form-control select2 @error('academic_session_id') is-invalid @enderror"
                                                name="academic_session_id" id="academic_session_id">
                                                <option value="">Select Academic Session</option>
                                                @if (isset($academic_sessions))
                                                    @foreach ($academic_sessions as $academic_session)
                                                        <option value="{{ $academic_session->id }}"
                                                            {{ old('academic_session_id', $student->academic_session_id ?? '') == $academic_session->id ? 'selected' : '' }}>
                                                            {{ $academic_session->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('class_id')
                                                <span class="invalid-feedback"
                                                    style="display:block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="class_id">Class <span class="text-danger">*</span></label>
                                            <select class="form-control select2 @error('class_id') is-invalid @enderror"
                                                name="class_id" id="class_id">
                                                <option value="">Select Class</option>
                                                @if (isset($classes))
                                                    @foreach ($classes as $class)
                                                        <option value="{{ $class->id }}"
                                                            {{ old('class_id', $student->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                                            {{ $class->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('class_id')
                                                <span class="invalid-feedback"
                                                    style="display:block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="admission_date">Admission Date</label>
                                            <input type="date"
                                                class="form-control @error('admission_date') is-invalid @enderror"
                                                name="admission_date" id="admission_date"
                                                value="{{ old('admission_date', isset($student) ? \Carbon\Carbon::parse($student->admission_date)->format('Y-m-d') : now()->format('Y-m-d')) }}">
                                            @error('admission_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="admission_status">Admission Status</label>
                                            <select
                                                class="form-control select2 @error('admission_status') is-invalid @enderror"
                                                name="admission_status" id="admission_status">
                                                <option value="pending"
                                                    {{ old('admission_status', $student->admission_status ?? '') == 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="submitted"
                                                    {{ old('admission_status', $student->admission_status ?? '') == 'submitted' ? 'selected' : '' }}>
                                                    Submitted</option>
                                                <option value="approved"
                                                    {{ old('admission_status', $student->admission_status ?? '') == 'approved' ? 'selected' : '' }}>
                                                    Approved</option>
                                                <option value="rejected"
                                                    {{ old('admission_status', $student->admission_status ?? '') == 'rejected' ? 'selected' : '' }}>
                                                    Rejected</option>
                                                <option value="fee_pending"
                                                    {{ old('admission_status', $student->admission_status ?? '') == 'fee_pending' ? 'selected' : '' }}>
                                                    Fee Pending</option>
                                                <option value="on_hold"
                                                    {{ old('admission_status', $student->admission_status ?? '') == 'on_hold' ? 'selected' : '' }}>
                                                    On
                                                    Hold</option>
                                                <option value="cancelled"
                                                    {{ old('admission_status', $student->admission_status ?? '') == 'cancelled' ? 'selected' : '' }}>
                                                    Cancelled</option>
                                            </select>
                                            @error('admission_status')
                                                <span class="invalid-feedback"
                                                    style="display:block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="father_name">Father's Name</label>
                                            <input type="text"
                                                class="form-control @error('father_name') is-invalid @enderror"
                                                name="father_name" id="father_name" placeholder="Enter father's name"
                                                value="{{ old('father_name', $student->father_name ?? '') }}">
                                            @error('father_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mother_name">Mother's Name</label>
                                            <input type="text"
                                                class="form-control @error('mother_name') is-invalid @enderror"
                                                name="mother_name" id="mother_name" placeholder="Enter mother's name"
                                                value="{{ old('mother_name', $student->mother_name ?? '') }}">
                                            @error('mother_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="profile_image">Profile Image</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file"
                                                        class="custom-file-input @error('profile_image') is-invalid @enderror"
                                                        name="profile_image" id="profile_image" accept="image/*">
                                                    <label class="custom-file-label" for="profile_image">Choose
                                                        image</label>
                                                </div>
                                            </div>
                                            @error('profile_image')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                            @if (isset($student) && $student->profile_image)
                                                <div class="mt-2" id="existing-profile-image-container">
                                                    <img src="{{ asset('storage/' . $student->profile_image) }}"
                                                        alt="Profile Image" class="img-thumbnail"
                                                        style="max-height: 100px;">
                                                    <button type="button" class="btn btn-sm btn-danger ml-2"
                                                        id="btn-remove-profile-image"><i class="fas fa-trash"></i>
                                                        Remove</button>
                                                </div>
                                                <input type="hidden" name="remove_profile_image"
                                                    id="remove_profile_image" value="0">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="documents">Document Upload </label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file"
                                                        class="custom-file-input @error('documents') is-invalid @enderror"
                                                        name="documents" id="documents">
                                                    <label class="custom-file-label" for="documents">Choose
                                                        file</label>
                                                </div>
                                            </div>
                                            @error('documents')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                            @if (isset($student) && $student->documents && !Str::startsWith($student->documents, '['))
                                                <div class="mt-2" id="existing-document-container">
                                                    <a href="{{ asset('storage/' . $student->documents) }}"
                                                        target="_blank" class="btn btn-sm btn-info"><i
                                                            class="fas fa-eye"></i> View Current Document</a>
                                                    <button type="button" class="btn btn-sm btn-danger ml-2"
                                                        id="btn-remove-document"><i class="fas fa-trash"></i>
                                                        Remove</button>
                                                </div>
                                                <input type="hidden" name="remove_document" id="remove_document"
                                                    value="0">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address">Address <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="3"
                                                placeholder="Enter address details">{{ old('address', $student->address ?? '') }}</textarea>
                                            @error('address')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <a href="{{ $moduleLink }}" class="btn btn-default mr-2">Cancel</a>
                                <button type="submit"
                                    class="btn btn-primary">{{ isset($student) ? 'Update' : 'Submit' }}</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>

    @section('script')
        <script>
            $(document).ready(function() {
                $('#SAForm').validate({
                    rules: {
                        name: {
                            required: true
                        },
                        admission_no: {
                            required: true
                        },
                        email: {
                            required: true,
                            email: true,
                        },
                        mobile: {
                            required: true,
                            digits: true,
                            minlength: 10,
                            maxlength: 12,
                        },
                        class_id: {
                            required: true
                        },
                        admission_date: {
                            required: true,
                            date: true
                        },
                        admission_status: {
                            required: true
                        },
                        address: {
                            required: true
                        },
                        documents: {
                            required: false
                        },
                        academic_session_id: {
                            required: true
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter the student name"
                        },
                        admission_no: {
                            required: "Please enter the admission number"
                        },
                        email: {
                            required: "Please enter an email address",
                            email: "Please enter a valid email address",
                        },
                        mobile: {
                            required: "Please enter a mobile number",
                            digits: "Please enter only digits",
                            minlength: "Mobile number must be at least 10 digits",
                            maxlength: "Mobile number must be at most 12 digits",
                        },
                        class_id: {
                            required: "Please select a class"
                        },
                        admission_date: {
                            required: "Please select an admission date",
                            date: "Please enter a valid date"
                        },
                        admission_status: {
                            required: "Please select an admission status"
                        },
                        address: {
                            required: "Please enter the address"
                        },
                        academic_session_id: {
                            required: "Please select an academic session"
                        }
                    },
                    errorElement: 'span',
                    ignore: [],
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        if (element.hasClass('select2') && element.next('.select2-container').length) {
                            error.insertAfter(element.next('.select2-container'));
                        } else if (element.parent('.custom-file').length) {
                            error.insertAfter(element.closest('.input-group'));
                        } else if (element.closest('.form-group').length) {
                            element.closest('.form-group').append(error);
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                        if ($(element).hasClass('select2')) {
                            $(element).next('.select2-container').find('.select2-selection').addClass(
                                'border-danger');
                        }
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                        if ($(element).hasClass('select2')) {
                            $(element).next('.select2-container').find('.select2-selection').removeClass(
                                'border-danger');
                        }
                    }
                });

                $('.select2').on('change', function() {
                    $(this).valid();
                });

                $(document).on('change', '.custom-file-input', function(e) {
                    var fileName = e.target.files[0] ? e.target.files[0].name : "Choose file";
                    $(this).next('.custom-file-label').html(fileName);
                });

                // Remove existing profile image
                $('#btn-remove-profile-image').click(function() {
                    $('#existing-profile-image-container').hide();
                    $('#remove_profile_image').val('1');
                });

                // Remove existing document
                $('#btn-remove-document').click(function() {
                    $('#existing-document-container').hide();
                    $('#remove_document').val('1');
                });
            });
        </script>
    @endsection
</x-app-layout>
