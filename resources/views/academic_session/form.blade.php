<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary mt-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ isset($academicSession) ? 'Edit Academic Session' : 'Create Academic Session' }}</h3>
                        </div>

                        <form
                            action="{{ isset($academicSession) ? route('academic.session.update', encrypt($academicSession->id)) : route('academic.session.store') }}"
                            method="POST" id="AcademicSessionForm">
                            @csrf
                            @if (isset($academicSession))
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
                                            <label for="name">Session Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                id="name" placeholder="e.g., 2025-2026"
                                                value="{{ old('name', $academicSession->name ?? '') }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="start_date">Start Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control @error('start_date') is-invalid @enderror"
                                                name="start_date" id="start_date"
                                                value="{{ old('start_date', $academicSession->start_date ?? '') }}">
                                            @error('start_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="end_date">End Date <span class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control @error('end_date') is-invalid @enderror"
                                                name="end_date" id="end_date"
                                                value="{{ old('end_date', $academicSession->end_date ?? '') }}">
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
                                                        {{ old('status', $academicSession->status ?? 1) == 1 ? 'checked' : '' }}>
                                                    <label for="statusActive"
                                                        class="custom-control-label">Active</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline">
                                                    <input
                                                        class="custom-control-input @error('status') is-invalid @enderror"
                                                        type="radio" id="statusInactive" name="status" value="0"
                                                        {{ old('status', $academicSession->status ?? 1) == 0 ? 'checked' : '' }}>
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
                                    class="btn btn-primary">{{ isset($academicSession) ? 'Update' : 'Submit' }}</button>
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
                $('#AcademicSessionForm').validate({
                    rules: {
                        name: {
                            required: true
                        },
                        start_date: {
                            required: true,
                            date: true
                        },
                        end_date: {
                            required: true,
                            date: true
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter the session name"
                        },
                        start_date: {
                            required: "Please select a start date",
                            date: "Please enter a valid date"
                        },
                        end_date: {
                            required: "Please select an end date",
                            date: "Please enter a valid date"
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
