<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12">

                    <div class="card card-info mt-3">
                        <div class="card-header">
                            <h3 class="card-title">View Student Details</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Student Name</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ $student->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="admission_no">Admission Number</label>
                                        <input type="text" class="form-control" name="admission_no"
                                            id="admission_no" value="{{ $student->admission_no }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Mobile Number</label>
                                        <input type="text" class="form-control" name="mobile" id="mobile"
                                            value="{{ $student->mobile }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control" name="email" id="email"
                                            value="{{ $student->email }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="class_id">Class</label>
                                        <input type="text" class="form-control" name="class_id" id="class_id"
                                            value="{{ $student->studentClasses ? $student->studentClasses->name : 'N/A' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="admission_date">Admission Date</label>
                                        <input type="text" class="form-control" name="admission_date"
                                            id="admission_date" value="{{ \Carbon\Carbon::parse($student->admission_date)->format('Y-m-d') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="admission_status">Admission Status</label>
                                        <input type="text" class="form-control text-capitalize" name="admission_status"
                                            id="admission_status" value="{{ str_replace('_', ' ', $student->admission_status) }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="father_name">Father's Name</label>
                                        <input type="text" class="form-control" name="father_name"
                                            id="father_name" value="{{ $student->father_name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mother_name">Mother's Name</label>
                                        <input type="text" class="form-control" name="mother_name"
                                            id="mother_name" value="{{ $student->mother_name }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Profile Image</label>
                                        <div class="mt-2">
                                            @if($student->profile_image)
                                                <img src="{{ asset('storage/' . $student->profile_image) }}" alt="Profile Image" class="img-thumbnail" style="max-height: 200px;">
                                            @else
                                                <p class="text-muted">No profile image uploaded.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Documents</label><br>
                                            @if($student->documents && !Str::startsWith($student->documents, '['))
                                                <a href="{{ asset('storage/' . $student->documents) }}" target="_blank" class="badge badge-primary badge-pill p-2"><i class="fas fa-download"></i> View / Download Document</a>
                                            @else
                                                <p class="text-muted">No document uploaded.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" name="address" id="address" rows="3"
                                            readonly>{{ $student->address }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('student.admission.management.index') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Back to List</a>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>
</x-app-layout>
