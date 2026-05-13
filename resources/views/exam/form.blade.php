<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary mt-3">
                        <div class="card-header">
                            <h3 class="card-title">{{ isset($exam) ? 'Edit Exam' : 'Create Exam' }}</h3>
                        </div>

                        <form
                            action="{{ isset($exam) ? route('exam.update', encrypt($exam->id)) : route('exam.store') }}"
                            method="POST" id="examForm">
                            @csrf
                            @if (isset($exam))
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
                                            <label for="academic_session_id">Academic Session <span
                                                    class="text-danger">*</span></label>
                                            <select
                                                class="form-control select2 @error('academic_session_id') is-invalid @enderror"
                                                name="academic_session_id" id="academic_session_id">
                                                <option value="">Select Session</option>
                                                @foreach ($sessions as $session)
                                                    <option value="{{ $session->id }}"
                                                        {{ old('academic_session_id', $exam->academic_session_id ?? '') == $session->id ? 'selected' : '' }}>
                                                        {{ $session->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('academic_session_id')
                                                <span class="invalid-feedback"
                                                    style="display:block;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Exam Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                id="name" placeholder="Enter exam name"
                                                value="{{ old('name', $exam->name ?? '') }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="subject_id">Subject <span class="text-danger">*</span></label>
                                            <select
                                                class="form-control select2 @error('subject_id') is-invalid @enderror"
                                                name="subject_id[]" id="subject_id" multiple>
                                                <option value="">Select Subject</option>
                                                @foreach ($subjects as $subject)
                                                    <option value="{{ $subject->id }}"
                                                        {{ isset($examSubjects) && in_array($subject->id, $examSubjects) ? 'selected' : '' }}>
                                                        {{ $subject->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('subject_id')
                                                <span class="invalid-feedback"
                                                    style="display:block;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date">Start Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control @error('start_date') is-invalid @enderror"
                                                name="start_date" id="start_date"
                                                value="{{ old('start_date', $exam->start_date ?? '') }}">
                                            @error('start_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_date">End Date <span class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control @error('end_date') is-invalid @enderror"
                                                name="end_date" id="end_date"
                                                value="{{ old('end_date', $exam->end_date ?? '') }}">
                                            @error('end_date')
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
                                                        type="radio" id="statusActive" name="status" value="1"
                                                        {{ old('status', $exam->status ?? 1) == 1 ? 'checked' : '' }}>
                                                    <label for="statusActive"
                                                        class="custom-control-label">Active</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline">
                                                    <input
                                                        class="custom-control-input @error('status') is-invalid @enderror"
                                                        type="radio" id="statusInactive" name="status" value="0"
                                                        {{ old('status', $exam->status ?? 1) == 0 ? 'checked' : '' }}>
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
                                <a href="{{ route('exam.index') }}" class="btn btn-default mr-2">Cancel</a>
                                <button type="submit"
                                    class="btn btn-primary">{{ isset($exam) ? 'Update' : 'Submit' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @section('script')
        <script>
            $(function() {
                $('.select2').select2({
                    theme: 'bootstrap4'
                }).on('change', function() {
                    $(this).valid();
                });

                $('#examForm').validate({
                    ignore: ':hidden:not(.select2-hidden-accessible)',
                    rules: {
                        name: {
                            required: true,
                            maxlength: 255
                        },
                        academic_session_id: {
                            required: true
                        },
                        start_date: {
                            required: true,
                            date: true
                        },
                        end_date: {
                            required: true,
                            date: true
                        },
                        status: {
                            required: true
                        },
                        "subject_id[]": {
                            required: true
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter exam name",
                            maxlength: "Exam name cannot exceed 255 characters"
                        },
                        academic_session_id: {
                            required: "Please select an academic session"
                        },
                        start_date: {
                            required: "Please provide a start date",
                            date: "Please enter a valid date"
                        },
                        end_date: {
                            required: "Please provide an end date",
                            date: "Please enter a valid date"
                        },
                        status: {
                            required: "Please select status"
                        },
                        "subject_id[]": {
                            required: "Please select at least one subject"
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
