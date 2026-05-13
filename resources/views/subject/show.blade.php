<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info mt-3">
                        <div class="card-header">
                            <h3 class="card-title">View Subject Details</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Subject Name</label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{ $subject->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Type</label>
                                        <input type="text" class="form-control" name="type" id="type" value="{{ ucfirst($subject->type) }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('subject.index') }}" class="btn btn-default"><i class="fas fa-arrow-left"></i> Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
