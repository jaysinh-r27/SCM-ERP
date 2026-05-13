<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ isset($section) ? 'Edit Section' : 'Create Section' }}</h3>
                        </div>

                        <form
                            action="{{ isset($section) ? route('section.update', encrypt($section->id)) : route('section.store') }}"
                            method="POST" id="SectionForm">
                            @csrf
                            @if (isset($section))
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
                                            <label for="name">Section Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                id="name" placeholder="Enter section name"
                                                value="{{ old('name', $section->name ?? '') }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="class_id">Class <span class="text-danger">*</span></label>
                                            <select class="form-control select2 @error('class_id') is-invalid @enderror"
                                                name="class_id" id="class_id">
                                                <option value="">Select Class</option>
                                                @if (isset($classes))
                                                    @foreach ($classes as $class)
                                                        <option value="{{ $class->id }}"
                                                            {{ old('class_id', $section->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                                            {{ $class->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('class_id')
                                                <span class="invalid-feedback"
                                                    style="display:block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="capacity">Capacity</label>
                                            <input type="number"
                                                class="form-control @error('capacity') is-invalid @enderror"
                                                name="capacity" id="capacity" placeholder="Enter capacity (e.g. 30)"
                                                value="{{ old('capacity', $section->capacity ?? '') }}">
                                            @error('capacity')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <div class="mt-2">
                                                <div class="custom-control custom-radio d-inline mr-3">
                                                    <input
                                                        class="custom-control-input @error('status') is-invalid @enderror"
                                                        type="radio" id="statusActive" name="status" value="1"
                                                        {{ old('status', $section->status ?? 1) == 1 ? 'checked' : '' }}>
                                                    <label for="statusActive"
                                                        class="custom-control-label">Active</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline">
                                                    <input
                                                        class="custom-control-input @error('status') is-invalid @enderror"
                                                        type="radio" id="statusInactive" name="status" value="0"
                                                        {{ old('status', $section->status ?? 1) == 0 ? 'checked' : '' }}>
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
                                    class="btn btn-primary">{{ isset($section) ? 'Update' : 'Submit' }}</button>
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
                $('#SectionForm').validate({
                    rules: {
                        name: {
                            required: true
                        },
                        class_id: {
                            required: true
                        },
                        capacity: {
                            required: false,
                            digits: true,
                            min: 1
                        },
                        status: {
                            required: true
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter the section name"
                        },
                        class_id: {
                            required: "Please select a class"
                        },
                        capacity: {
                            digits: "Please enter a valid number",
                            min: "Capacity must be at least 1"
                        },
                        status: {
                            required: "Please select a status"
                        }
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        if (element.hasClass('select2') && element.next('.select2-container').length) {
                            error.insertAfter(element.next('.select2-container'));
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
            });
        </script>
    @endsection
</x-app-layout>
