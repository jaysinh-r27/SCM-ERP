<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info mt-3">
                        <div class="card-header">
                            <h3 class="card-title">View User Details</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control" value="{{ $user->email }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control" value="{{ $user->phone }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Role</label>
                                        <input type="text" class="form-control"
                                            value="{{ $role ? $role->name : 'N/A' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <div class="mt-2">
                                            <div class="custom-control custom-radio d-inline mr-3">
                                                <input class="custom-control-input" type="radio" id="statusActive"
                                                    value="1" {{ $user->status == 1 ? 'checked' : '' }} disabled>
                                                <label for="statusActive" class="custom-control-label">Active</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline">
                                                <input class="custom-control-input" type="radio" id="statusInactive"
                                                    value="0" {{ $user->status == 0 ? 'checked' : '' }} disabled>
                                                <label for="statusInactive"
                                                    class="custom-control-label">Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('user.index') }}" class="btn btn-default"><i
                                    class="fas fa-arrow-left"></i> Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
