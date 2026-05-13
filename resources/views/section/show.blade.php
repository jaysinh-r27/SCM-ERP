<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info mt-3">
                        <div class="card-header">
                            <h3 class="card-title">View Section Details</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Section Name</label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{ $section->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class_id">Class Name</label>
                                        <input type="text" class="form-control" name="class_id" id="class_id" value="{{ $section->studentClass ? $section->studentClass->name : 'N/A' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="capacity">Capacity</label>
                                        <input type="text" class="form-control" name="capacity" id="capacity" value="{{ $section->capacity ?? 'N/A' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <input type="text" class="form-control {{ $section->status == 1 ? 'text-success' : 'text-danger' }}" name="status" id="status" value="{{ $section->status == 1 ? 'Active' : 'Inactive' }}" readonly>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('section.index') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
