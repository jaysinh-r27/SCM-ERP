<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary mt-3">
                        <div class="card-header">
                            <h3 class="card-title">{{ isset($staff) ? 'Edit Staff' : 'Add Staff' }}</h3>
                        </div>
                        <form
                            action="{{ isset($staff) ? route('staff.update', encrypt($staff->id)) : route('staff.store') }}"
                            method="POST" id="StaffForm">
                            @csrf
                            @if (isset($staff))
                                @method('PUT')
                            @endif
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                id="name" placeholder="Enter name"
                                                value="{{ old('name', $staff->name ?? '') }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                id="email" placeholder="Enter email"
                                                value="{{ old('email', $staff->email ?? '') }}">
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text"
                                                class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                id="phone" placeholder="Enter phone" maxlength="12"
                                                value="{{ old('phone', $staff->phone ?? '') }}">
                                            @error('phone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password {!! !isset($staff) ? '<span class="text-danger">*</span>' : '' !!}</label>
                                            <div class="input-group">
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" id="password" placeholder="Enter password">
                                                <div class="input-group-append">
                                                    <span class="input-group-text toggle-password"
                                                        data-target="#password" style="cursor: pointer;"><i
                                                            class="fas fa-eye"></i></span>
                                                </div>
                                                @error('password')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            @if (isset($staff))
                                                <small class="form-text text-muted">Leave blank if you don't want to
                                                    change the password.</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password
                                                {!! !isset($staff) ? '<span class="text-danger">*</span>' : '' !!}</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="password_confirmation"
                                                    id="password_confirmation" placeholder="Confirm password">
                                                <div class="input-group-append">
                                                    <span class="input-group-text toggle-password"
                                                        data-target="#password_confirmation" style="cursor: pointer;"><i
                                                            class="fas fa-eye"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="subject_id">Subject <span class="text-danger">*</span></label>
                                            <select
                                                class="form-control select2 @error('subject_id') is-invalid @enderror"
                                                name="subject_id" id="subject_id">
                                                <option value="">Select Subject</option>
                                                @foreach ($subjects as $subject)
                                                    <option value="{{ $subject->id }}"
                                                        {{ old('subject_id', $staff->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                                                        {{ $subject->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('subject_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="qualification">Qualification</label>
                                            <input type="text"
                                                class="form-control @error('qualification') is-invalid @enderror"
                                                name="qualification" id="qualification" placeholder="e.g. M.Sc, B.Ed"
                                                value="{{ old('qualification', $staff->qualification ?? '') }}">
                                            @error('qualification')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="joining_date">Joining Date</label>
                                            <input type="date"
                                                class="form-control @error('joining_date') is-invalid @enderror"
                                                name="joining_date" id="joining_date"
                                                value="{{ old('joining_date', isset($staff) && $staff->staffProfile ? $staff->joining_date : now()->format('Y-m-d')) }}">
                                            @error('joining_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="basic_salary">Basic Salary</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('basic_salary') is-invalid @enderror"
                                                name="basic_salary" id="basic_salary" placeholder="0.00"
                                                value="{{ old('basic_salary', $staff->basic_salary ?? '') }}">
                                            @error('basic_salary')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="account_number">Account Number</label>
                                            <input type="text"
                                                class="form-control @error('account_number') is-invalid @enderror"
                                                name="account_number" id="account_number"
                                                placeholder="Enter account number"
                                                value="{{ old('account_number', $staff->account_number ?? '') }}">
                                            @error('account_number')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="bank_name">Bank Name</label>
                                            <input type="text"
                                                class="form-control @error('bank_name') is-invalid @enderror"
                                                name="bank_name" id="bank_name" placeholder="Enter bank name"
                                                value="{{ old('bank_name', $staff->bank_name ?? '') }}">
                                            @error('bank_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="ifsc_code">IFSC Code</label>
                                            <input type="text"
                                                class="form-control @error('ifsc_code') is-invalid @enderror"
                                                name="ifsc_code" id="ifsc_code" placeholder="Enter IFSC code"
                                                value="{{ old('ifsc_code', $staff->ifsc_code ?? '') }}">
                                            @error('ifsc_code')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <div class="mt-2">
                                                <div class="custom-control custom-radio d-inline mr-3">
                                                    <input
                                                        class="custom-control-input @error('status') is-invalid @enderror"
                                                        type="radio" id="statusActive" name="status"
                                                        value="1"
                                                        {{ old('status', $staff->status ?? 1) == 1 ? 'checked' : '' }}>
                                                    <label for="statusActive"
                                                        class="custom-control-label">Active</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline">
                                                    <input
                                                        class="custom-control-input @error('status') is-invalid @enderror"
                                                        type="radio" id="statusInactive" name="status"
                                                        value="0"
                                                        {{ old('status', $staff->status ?? 1) == 0 ? 'checked' : '' }}>
                                                    <label for="statusInactive"
                                                        class="custom-control-label">Inactive</label>
                                                </div>
                                            </div>
                                            @error('status')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ $moduleLink }}" class="btn btn-default mr-2">Cancel</a>
                                <button type="submit"
                                    class="btn btn-primary">{{ isset($staff) ? 'Update' : 'Submit' }}</button>
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
                $('.select2').select2({
                    theme: 'bootstrap4'
                }).on('change', function() {
                    $(this).valid();
                });

                $('.toggle-password').click(function() {
                    var targetId = $(this).data('target');
                    var input = $(targetId);
                    var icon = $(this).find('i');
                    if (input.attr('type') === 'password') {
                        input.attr('type', 'text');
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        input.attr('type', 'password');
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });

                $('#StaffForm').validate({
                    ignore: ':hidden:not(.select2-hidden-accessible)',
                    rules: {
                        name: {
                            required: true,
                            maxlength: 255
                        },
                        email: {
                            required: true,
                            email: true,
                            maxlength: 255,
                            remote: {
                                url: "{{ route('staff.checkEmail') }}",
                                type: "get",
                                data: {
                                    email: function() {
                                        return $("#email").val();
                                    },
                                    id: "{{ isset($staff) ? encrypt($staff->id) : '' }}"
                                }
                            }
                        },
                        phone: {
                            maxlength: 12,
                            digits: true
                        },
                        password: {
                            required: {{ isset($staff) ? 'false' : 'true' }},
                            minlength: 8
                        },
                        password_confirmation: {
                            required: function() {
                                return $('#password').val().length > 0;
                            },
                            equalTo: "#password"
                        },
                        subject_id: {
                            required: true
                        },
                        basic_salary: {
                            number: true,
                            min: 0
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter the name",
                            maxlength: "Name cannot exceed 255 characters"
                        },
                        email: {
                            required: "Please enter an email address",
                            email: "Please enter a valid email address",
                            maxlength: "Email cannot exceed 255 characters",
                            remote: "This email is already taken"
                        },
                        phone: {
                            maxlength: "Phone number cannot exceed 12 digits",
                            digits: "Please enter only digits"
                        },
                        password: {
                            required: "Please provide a password",
                            minlength: "Password must be at least 8 characters long"
                        },
                        password_confirmation: {
                            required: "Please confirm your password",
                            equalTo: "Passwords do not match"
                        },
                        subject_id: {
                            required: "Please select a subject"
                        },
                        basic_salary: {
                            number: "Please enter a valid number",
                            min: "Salary cannot be negative"
                        }
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else if (element.hasClass('select2-hidden-accessible')) {
                            error.insertAfter(element.next('.select2-container'));
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                        if ($(element).hasClass('select2-hidden-accessible')) {
                            $(element).next('.select2-container').find('.select2-selection').addClass(
                                'border-danger');
                        }
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                        if ($(element).hasClass('select2-hidden-accessible')) {
                            $(element).next('.select2-container').find('.select2-selection').removeClass(
                                'border-danger');
                        }
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>
