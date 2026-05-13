<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Select Details</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('exam-result.marks-entry') }}" method="GET" id="filterForm">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Exam <span class="text-danger">*</span></label>
                                            <select name="exam_id"
                                                class="form-control select2 @error('exam_id') is-invalid @enderror"
                                                id="exam_id" required>
                                                <option value="">Select Exam</option>
                                                @foreach ($exams as $exam)
                                                    <option value="{{ encrypt($exam->id) }}"
                                                        {{ isset($selected_exam_id) && $selected_exam_id == $exam->id ? 'selected' : '' }}>
                                                        {{ $exam->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Student <span class="text-danger">*</span></label>
                                            <select name="student_id"
                                                class="form-control select2 @error('student_id') is-invalid @enderror"
                                                id="student_id" required>
                                                <option value="">Select Student</option>
                                                @foreach ($students as $student)
                                                    <option value="{{ encrypt($student->id) }}"
                                                        {{ isset($selected_student_id) && $selected_student_id == $student->id ? 'selected' : '' }}>
                                                        {{ $student->name }} ({{ $student->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2" style="margin-top: 32px;">
                                        <button type="submit" class="btn btn-primary btn-block"><i
                                                class="fas fa-search"></i> Fetch</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if (isset($selected_exam_id) && isset($selected_student_id))
                        <div class="card card-primary mt-3">
                            <div class="card-header">
                                <h3 class="card-title">Enter Marks (Max 100 per Subject)</h3>
                            </div>
                            <form action="{{ route('exam-result.store-marks') }}" method="POST" id="marksForm">
                                @csrf
                                <input type="hidden" name="exam_id" value="{{ encrypt($selected_exam_id) }}">
                                <input type="hidden" name="student_id" value="{{ encrypt($selected_student_id) }}">
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-check"></i> Success!</h5>
                                            {{ session('success') }}
                                        </div>
                                    @endif
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
                                        <div class="col-md-8 offset-md-2">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Subject Name</th>
                                                        <th style="width: 250px;">Obtained Marks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($examSubject))
                                                        @foreach ($examSubject as $subjectId => $subjectName)
                                                            <tr>
                                                                <td style="vertical-align: middle;">
                                                                    {{ $subjectName }}
                                                                </td>
                                                                <td>
                                                                    <div class="form-group mb-0">
                                                                        <input type="number" step="0.01"
                                                                            min="0" max="100"
                                                                            name="marks[{{ $subjectId }}]"
                                                                            class="form-control"
                                                                            placeholder="Enter marks"
                                                                            value="{{ $marks[$subjectId] ?? '' }}">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <a href="{{ route('exam-result.index') }}" class="btn btn-default mr-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save
                                        Marks</button>
                                </div>
                            </form>
                        </div>
                    @endif
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

                $('#filterForm').validate({
                    ignore: ':hidden:not(.select2-hidden-accessible)',
                    rules: {
                        exam_id: {
                            required: true
                        },
                        student_id: {
                            required: true
                        }
                    },
                    messages: {
                        exam_id: {
                            required: "Please select an exam"
                        },
                        student_id: {
                            required: "Please select a student"
                        }
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        if (element.hasClass('select2-hidden-accessible')) {
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

                $('#marksForm').validate({
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        error.insertAfter(element);
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    }
                });

                $('input[name^="marks["]').each(function() {
                    $(this).rules("add", {
                        min: 0,
                        max: 100,
                        number: true,
                        messages: {
                            min: "Minimum 0",
                            max: "Maximum 100",
                            number: "Please enter a valid number"
                        }
                    });
                });
            });
        </script>
    @endsection
</x-app-layout>
