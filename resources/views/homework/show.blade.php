<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info mt-3">
                        <div class="card-header">
                            <h3 class="card-title">View Homework Details</h3>
                        </div>

                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="class_id">Class</label>
                                        <input type="text" class="form-control" id="class_id"
                                            value="{{ $homework->class ? $homework->class->name : 'N/A' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subject_id">Subject</label>
                                        <input type="text" class="form-control" id="subject_id"
                                            value="{{ $homework->subject ? $homework->subject->name : 'N/A' }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="title">Homework Title</label>
                                        <input type="text" class="form-control" id="title"
                                            value="{{ $homework->title }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" rows="3" readonly>{{ $homework->description }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date">Homework Date</label>
                                        <input type="text" class="form-control" id="date"
                                            value="{{ \Carbon\Carbon::parse($homework->date)->format('d-m-Y') }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="submission_date">Submission Date</label>
                                        <input type="text" class="form-control" id="submission_date"
                                            value="{{ \Carbon\Carbon::parse($homework->submission_date)->format('d-m-Y') }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Homework Image</label>
                                        <div class="mt-2">
                                            @if ($homework->file_upload)
                                                <img src="{{ Storage::url($homework->file_upload) }}"
                                                    alt="Homework Image" class="img-thumbnail"
                                                    style="max-height: 200px;">
                                                <div class="mt-2">
                                                    <a href="{{ Storage::url($homework->file_upload) }}" target="_blank"
                                                        class="badge badge-info p-2">
                                                        <i class="fas fa-image"></i> View Full Image
                                                    </a>
                                                </div>
                                            @else
                                                <p class="text-muted">No image uploaded.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Study Material</label>
                                        <div class="mt-2">
                                            @if ($homework->study_material_upload)
                                                <a href="{{ Storage::url($homework->study_material_upload) }}"
                                                    target="_blank" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-file-download"></i> View / Download Study Material
                                                </a>
                                            @else
                                                <p class="text-muted">No study material uploaded.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('homework.index') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
