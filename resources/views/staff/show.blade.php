<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info mt-3">
                        <div class="card-header">
                            <h3 class="card-title">View Staff Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" value="{{ $staff->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control" value="{{ $staff->email }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" value="{{ $staff->phone ?? 'N/A' }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Subject</label>
                                        <input type="text" class="form-control"
                                            value="{{ $staff->subject ? $staff->subject->name : 'N/A' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Qualification</label>
                                        <input type="text" class="form-control"
                                            value="{{ $staff->qualification ?? 'N/A' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Joining Date</label>
                                        <input type="text" class="form-control"
                                            value="{{ $staff->joining_date ? \Carbon\Carbon::parse($staff->joining_date)->format('d-m-Y') : 'N/A' }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Basic Salary</label>
                                        <input type="text" class="form-control"
                                            value="{{ number_format($staff->basic_salary, 2) }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Account Number</label>
                                        <input type="text" class="form-control"
                                            value="{{ $staff->account_number ?? 'N/A' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Bank Name</label>
                                        <input type="text" class="form-control"
                                            value="{{ $staff->bank_name ?? 'N/A' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>IFSC Code</label>
                                        <input type="text" class="form-control"
                                            value="{{ $staff->ifsc_code ?? 'N/A' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <div class="mt-2">
                                            <div class="custom-control custom-radio d-inline mr-3">
                                                <input class="custom-control-input" type="radio" id="statusActive"
                                                    value="1" {{ $staff->status == 1 ? 'checked' : '' }} disabled>
                                                <label for="statusActive" class="custom-control-label">Active</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline">
                                                <input class="custom-control-input" type="radio" id="statusInactive"
                                                    value="0" {{ $staff->status == 0 ? 'checked' : '' }} disabled>
                                                <label for="statusInactive"
                                                    class="custom-control-label">Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('staff.index') }}" class="btn btn-default"><i
                                    class="fas fa-arrow-left"></i> Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
