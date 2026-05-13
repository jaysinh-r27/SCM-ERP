<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary mt-3">
                        <div class="card-header">
                            <h3 class="card-title">{{ isset($user) ? 'Edit User' : 'Create User' }}</h3>
                        </div>

                        <form
                            action="{{ isset($user) ? route('user.update', encrypt($user->id)) : route('user.store') }}"
                            method="POST" id="UserForm">
                            @csrf
                            @if (isset($user))
                                @method('PUT')
                                <input type="hidden" id="user_id" name="user_id" value="{{ $user->id }}">
                            @else
                                <input type="hidden" id="user_id" name="user_id" value="">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                id="name" placeholder="Enter name"
                                                value="{{ old('name', $user->name ?? '') }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                id="email" placeholder="Enter email"
                                                value="{{ old('email', $user->email ?? '') }}">
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text"
                                                class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                id="phone" placeholder="Enter phone" maxlength="12"
                                                value="{{ old('phone', $user->phone ?? '') }}">
                                            @error('phone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role_id">Role <span class="text-danger">*</span></label>
                                            <select class="form-control select2 @error('role_id') is-invalid @enderror"
                                                name="role_id" id="role_id">
                                                <option value="">Select Role</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ old('role_id', $userRole ?? '') == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <span class="invalid-feedback"
                                                    style="display:block;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password {!! !isset($user) ? '<span class="text-danger">*</span>' : '' !!}</label>
                                            <div class="input-group">
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" id="password" placeholder="Enter password">
                                                <div class="input-group-append">
                                                    <span class="input-group-text toggle-password"
                                                        data-target="#password" style="cursor: pointer;">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                                @error('password')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            @if (isset($user))
                                                <small class="form-text text-muted">Leave blank if you don't want to
                                                    change the password.</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password
                                                {!! !isset($user) ? '<span class="text-danger">*</span>' : '' !!}</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="password_confirmation"
                                                    id="password_confirmation" placeholder="Confirm password">
                                                <div class="input-group-append">
                                                    <span class="input-group-text toggle-password"
                                                        data-target="#password_confirmation" style="cursor: pointer;">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
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
                                                        {{ old('status', $user->status ?? 1) == 1 ? 'checked' : '' }}>
                                                    <label for="statusActive"
                                                        class="custom-control-label">Active</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline">
                                                    <input
                                                        class="custom-control-input @error('status') is-invalid @enderror"
                                                        type="radio" id="statusInactive" name="status"
                                                        value="0"
                                                        {{ old('status', $user->status ?? 1) == 0 ? 'checked' : '' }}>
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
                                    class="btn btn-primary">{{ isset($user) ? 'Update' : 'Submit' }}</button>
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

                $('#UserForm').validate({
                    ignore: ':hidden:not(.select2-hidden-accessible)',
                    rules: {
                        name: {
                            required: true,
                            maxlength: 255
                        },
                        email: {
                            required: true,
                            email: true,
                            maxlength: 255
                        },
                        phone: {
                            maxlength: 12,
                            digits: true
                        },
                        password: {
                            required: {{ isset($user) ? 'false' : 'true' }},
                            minlength: 8
                        },
                        password_confirmation: {
                            required: function() {
                                return $('#password').val().length > 0;
                            },
                            equalTo: "#password"
                        },
                        role_id: {
                            required: true
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
                            maxlength: "Email cannot exceed 255 characters"
                        },
                        phone: {
                            maxlength: "Phone number cannot exceed 12 digits",
                            digits: "Please enter only digits"
                        },
                        password: {
                            required: "Please provide a password",
                            minlength: "Your password must be at least 6 characters long"
                        },
                        password_confirmation: {
                            required: "Please confirm your password",
                            equalTo: "Passwords do not match"
                        },
                        role_id: {
                            required: "Please select a role"
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
