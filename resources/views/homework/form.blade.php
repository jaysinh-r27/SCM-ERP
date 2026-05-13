<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary mt-3">
                        <div class="card-header">
                            <h3 class="card-title">{{ isset($homework) ? 'Edit Homework' : 'Assign Homework' }}</h3>
                        </div>

                        <form
                            action="{{ isset($homework) ? route('homework.update', encrypt($homework->id)) : route('homework.store') }}"
                            method="POST" id="homeworkForm" enctype="multipart/form-data">
                            @csrf
                            @if (isset($homework))
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="class_id">Class <span class="text-danger">*</span></label>
                                            <select class="form-control select2 @error('class_id') is-invalid @enderror"
                                                name="class_id" id="class_id">
                                                <option value="">Select Class</option>
                                                @foreach ($classes as $class)
                                                    <option value="{{ $class->id }}"
                                                        {{ old('class_id', $homework->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                                        {{ $class->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('class_id')
                                                <span class="invalid-feedback"
                                                    style="display:block;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="subject_id">Subject <span class="text-danger">*</span></label>
                                            <select
                                                class="form-control select2 @error('subject_id') is-invalid @enderror"
                                                name="subject_id" id="subject_id">
                                                <option value="">Select Subject</option>
                                                @foreach ($subjects as $subject)
                                                    <option value="{{ $subject->id }}"
                                                        {{ old('subject_id', $homework->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title">Homework Title <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('title') is-invalid @enderror" name="title"
                                                id="title" placeholder="Enter title"
                                                value="{{ old('title', $homework->title ?? '') }}">
                                            @error('title')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Description <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                                                rows="3" placeholder="Enter detailed description">{{ old('description', $homework->description ?? '') }}</textarea>
                                            @error('description')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date">Homework Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control @error('date') is-invalid @enderror" name="date"
                                                id="date" value="{{ old('date', $homework->date ?? '') }}">
                                            @error('date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="submission_date">Submission Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control @error('submission_date') is-invalid @enderror"
                                                name="submission_date" id="submission_date"
                                                value="{{ old('submission_date', $homework->submission_date ?? '') }}">
                                            @error('submission_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="file_upload">Homework Image (jpg, png)</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file"
                                                        class="custom-file-input @error('file_upload') is-invalid @enderror"
                                                        name="file_upload" id="file_upload"
                                                        accept="image/png, image/jpeg, image/jpg">
                                                    <label class="custom-file-label" for="file_upload">Choose
                                                        image</label>
                                                </div>
                                            </div>
                                            @error('file_upload')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror

                                            @if (isset($homework) && $homework->file_upload)
                                                <div class="mt-2">
                                                    <a href="{{ Storage::url($homework->file_upload) }}"
                                                        target="_blank" class="badge badge-info p-2">
                                                        <i class="fas fa-image"></i> View Current Image
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="study_material_upload">Study Material (PDF, DOCX)</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file"
                                                        class="custom-file-input @error('study_material_upload') is-invalid @enderror"
                                                        name="study_material_upload" id="study_material_upload"
                                                        accept=".pdf,.doc,.docx">
                                                    <label class="custom-file-label"
                                                        for="study_material_upload">Choose file</label>
                                                </div>
                                            </div>
                                            @error('study_material_upload')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror

                                            @if (isset($homework) && $homework->study_material_upload)
                                                <div class="mt-2">
                                                    <a href="{{ Storage::url($homework->study_material_upload) }}"
                                                        target="_blank" class="badge badge-secondary p-2">
                                                        <i class="fas fa-file-pdf"></i> View Current Material
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ route('homework.index') }}" class="btn btn-default mr-2">Cancel</a>
                                <button type="submit"
                                    class="btn btn-primary">{{ isset($homework) ? 'Update' : 'Submit' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @section('script')
        <script>
            $.validator.addMethod("extension", function(value, element, param) {
                param = typeof param === "string" ? param.replace(/,/g, "|") : "png|jpe?g|gif";
                return this.optional(element) || value.match(new RegExp("\\\.(" + param + ")$", "i"));
            }, "Please upload a file with a valid extension.");

            $(document).ready(function() {
                $('#homeworkForm').validate({
                    ignore: [],
                    rules: {
                        class_id: {
                            required: true
                        },
                        subject_id: {
                            required: true
                        },
                        title: {
                            required: true,
                            maxlength: 255
                        },
                        description: {
                            required: true
                        },
                        date: {
                            required: true,
                            date: true
                        },
                        submission_date: {
                            required: true,
                            date: true
                        },
                        file_upload: {
                            accept: "",
                            extension: "jpg|jpeg|png"
                        },
                        study_material_upload: {
                            accept: "",
                            extension: "pdf|doc|docx"
                        }
                    },
                    messages: {
                        class_id: {
                            required: "Please select a class"
                        },
                        subject_id: {
                            required: "Please select a subject"
                        },
                        title: {
                            required: "Please enter homework title",
                            maxlength: "Title cannot exceed 255 characters"
                        },
                        description: {
                            required: "Please enter description"
                        },
                        date: {
                            required: "Please select date"
                        },
                        submission_date: {
                            required: "Please select submission date"
                        },
                        file_upload: {
                            extension: "Only JPG, JPEG, and PNG images are allowed"
                        },
                        study_material_upload: {
                            extension: "Only PDF, DOC, and DOCX files are allowed"
                        }
                    },
                    errorElement: 'span',
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

                $('.select2').select2({
                    theme: 'bootstrap4'
                }).on('change', function() {
                    $(this).valid();
                });

                $(document).on('change', '.custom-file-input', function(e) {
                    var fileName = e.target.files[0] ? e.target.files[0].name : "Choose file";
                    $(this).next('.custom-file-label').html(fileName);
                });
            });
        </script>
    @endsection
</x-app-layout>
