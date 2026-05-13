<x-app-layout>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Assign Fee to Student</h3>
                        </div>
                        <form action="{{ route('fee.collection.store.assign') }}" method="POST" id="AssignFeeForm">
                            @csrf
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
                                <div class="form-group">
                                    <label>Student <span class="text-danger">*</span></label>
                                    <select name="student_id"
                                        class="form-control select2 @error('student_id') is-invalid @enderror"
                                        id="student_id">
                                        <option value="">Select Student</option>
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}">{{ $student->name }}
                                                ({{ $student->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Fee Category <span class="text-danger">*</span></label>
                                    <select name="fee_category_id"
                                        class="form-control select2 @error('fee_category_id') is-invalid @enderror"
                                        id="fee_category_id">
                                        <option value="">Select Fee Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }} -
                                                {{ $category->amount }}</option>
                                        @endforeach
                                    </select>
                                    @error('fee_category_id')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Due Date</label>
                                    <input type="date" name="due_date"
                                        class="form-control @error('due_date') is-invalid @enderror" id="due_date">
                                    @error('due_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ route('fee.collection.dashboard') }}"
                                    class="btn btn-default mr-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Assign Fee</button>
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

                $('#AssignFeeForm').validate({
                    ignore: ':hidden:not(.select2-hidden-accessible)',
                    rules: {
                        student_id: {
                            required: true
                        },
                        fee_category_id: {
                            required: true
                        },
                        due_date: {
                            date: true
                        }
                    },
                    messages: {
                        student_id: {
                            required: "Please select a student"
                        },
                        fee_category_id: {
                            required: "Please select a fee category"
                        },
                        due_date: {
                            date: "Please enter a valid date"
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
            });
        </script>
    @endsection
</x-app-layout>
