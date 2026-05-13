<x-app-layout>

<section class="content">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ isset($category) ? 'Edit' : 'Create' }} Fee Category</h3>
                </div>
                <form action="{{ isset($category) ? route('fee-category.update', encrypt($category->id)) : route('fee-category.store') }}" method="POST" id="FeeCategoryForm">
                    @csrf
                    @if(isset($category))
                        @method('PUT')
                    @endif
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-ban"></i> Please fix the following errors:</h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter name" value="{{ old('name', $category->name ?? '') }}">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Enter description">{{ old('description', $category->description ?? '') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" step="0.01" placeholder="Enter amount" value="{{ old('amount', $category->amount ?? '') }}">
                            @error('amount')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <div class="mt-2">
                                <div class="custom-control custom-radio d-inline mr-3">
                                    <input class="custom-control-input @error('status') is-invalid @enderror" type="radio" id="statusActive" name="status" value="1" {{ old('status', $category->status ?? 1) == 1 ? 'checked' : '' }}>
                                    <label for="statusActive" class="custom-control-label">Active</label>
                                </div>
                                <div class="custom-control custom-radio d-inline">
                                    <input class="custom-control-input @error('status') is-invalid @enderror" type="radio" id="statusInactive" name="status" value="0" {{ old('status', $category->status ?? 1) == 0 ? 'checked' : '' }}>
                                    <label for="statusInactive" class="custom-control-label">Inactive</label>
                                </div>
                            </div>
                            @error('status')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('fee-category.index') }}" class="btn btn-default mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Update' : 'Submit' }}</button>
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
        $('#FeeCategoryForm').validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255
                },
                amount: {
                    required: true,
                    number: true,
                    min: 0
                },
                status: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Please enter the category name",
                    maxlength: "Name cannot exceed 255 characters"
                },
                amount: {
                    required: "Please enter the amount",
                    number: "Please enter a valid number",
                    min: "Amount must be greater than or equal to 0"
                },
                status: {
                    required: "Please select a status"
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.attr("type") == "radio") {
                    error.insertAfter(element.closest('.mt-2'));
                    error.addClass('d-block');
                } else {
                    error.insertAfter(element);
                }
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
