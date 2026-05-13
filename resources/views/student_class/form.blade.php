<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ isset($studentClass) ? 'Edit Class' : 'Create Class' }}</h3>
                        </div>

                        <form
                            action="{{ isset($studentClass) ? route('student.class.update', encrypt($studentClass->id)) : route('student.class.store') }}"
                            method="POST" id="ClassForm">
                            @csrf
                            @if (isset($studentClass))
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
                                            <label for="name">Class Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                id="name" placeholder="Enter class name"
                                                value="{{ old('name', $studentClass->name ?? '') }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Type <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type') is-invalid @enderror"
                                                name="type" id="type">
                                                <option value="school"
                                                    {{ old('type', $studentClass->type ?? 'school') == 'school' ? 'selected' : '' }}>
                                                    School</option>
                                                <option value="college"
                                                    {{ old('type', $studentClass->type ?? '') == 'college' ? 'selected' : '' }}>
                                                    College</option>
                                            </select>
                                            @error('type')
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
                                                        {{ old('status', $studentClass->status ?? 1) == 1 ? 'checked' : '' }}>
                                                    <label for="statusActive"
                                                        class="custom-control-label">Active</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline">
                                                    <input
                                                        class="custom-control-input @error('status') is-invalid @enderror"
                                                        type="radio" id="statusInactive" name="status" value="0"
                                                        {{ old('status', $studentClass->status ?? 1) == 0 ? 'checked' : '' }}>
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
                                    class="btn btn-primary">{{ isset($studentClass) ? 'Update' : 'Submit' }}</button>
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
                $('#ClassForm').validate({
                    rules: {
                        name: {
                            required: true
                        },
                        type: {
                            required: true
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter the class name"
                        },
                        type: {
                            required: "Please select a type"
                        }
                    },
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
            });
        </script>
    @endsection
</x-app-layout>
