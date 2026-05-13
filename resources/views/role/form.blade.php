<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary mt-3">
                        <div class="card-header">
                            <h3 class="card-title">{{ isset($role) ? 'Edit Role' : 'Create Role' }}</h3>
                        </div>

                        <form
                            action="{{ isset($role) ? route('role.update', encrypt($role->id)) : route('role.store') }}"
                            method="POST" id="RoleForm">
                            @csrf
                            @if (isset($role))
                                @method('PUT')
                                <input type="hidden" id="role_id" name="role_id" value="{{ $role->id }}">
                            @else
                                <input type="hidden" id="role_id" name="role_id" value="">
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
                                            <label for="name">Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                id="name" placeholder="Enter role name"
                                                value="{{ old('name', $role->name ?? '') }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                                                rows="3" placeholder="Enter description">{{ old('description', $role->description ?? '') }}</textarea>
                                            @error('description')
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
                                                        {{ old('status', $role->status ?? 1) == 1 ? 'checked' : '' }}>
                                                    <label for="statusActive"
                                                        class="custom-control-label">Active</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline">
                                                    <input
                                                        class="custom-control-input @error('status') is-invalid @enderror"
                                                        type="radio" id="statusInactive" name="status" value="0"
                                                        {{ old('status', $role->status ?? 1) == 0 ? 'checked' : '' }}>
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
                                <hr>

                                <!-- Permissions Section -->
                                <div class="form-group">
                                    <label>Permissions <span class="text-danger">*</span></label>
                                    <div class="row">
                                        @foreach ($permissions as $moduleName => $modulePermissions)
                                            <div class="col-md-3 mb-3">
                                                <div class="card card-outline card-primary">
                                                    <div class="card-header">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox"
                                                                class="custom-control-input module-checkbox"
                                                                id="module_{{ \Illuminate\Support\Str::slug($moduleName) }}">
                                                            <label class="custom-control-label"
                                                                for="module_{{ \Illuminate\Support\Str::slug($moduleName) }}">
                                                                <h3 class="card-title" style="margin-top: 2px;">
                                                                    {{ ucfirst($moduleName) }}</h3>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        @foreach ($modulePermissions as $permission)
                                                            <div class="form-check">
                                                                <input type="checkbox" name="permissions[]"
                                                                    value="{{ $permission->id }}"
                                                                    class="form-check-input permission-checkbox"
                                                                    id="permission_{{ $permission->id }}"
                                                                    {{ isset($role) && $role->permissions->contains('permission_id', $permission->id) ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="permission_{{ $permission->id }}">
                                                                    {{ ucfirst($permission->name) }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('permissions')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ $moduleLink }}" class="btn btn-default mr-2">Cancel</a>
                                <button type="submit"
                                    class="btn btn-primary">{{ isset($role) ? 'Update' : 'Submit' }}</button>
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
                $('#RoleForm').validate({
                    rules: {
                        name: {
                            required: true,
                            maxlength: 255,
                            remote: {
                                url: "{{ route('role.checkName') }}",
                                type: "GET",
                                data: {
                                    name: function() {
                                        return $("#name").val();
                                    },
                                    role_id: function() {
                                        return $("#role_id").val();
                                    }
                                }
                            }
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter the role name",
                            maxlength: "Name cannot exceed 255 characters",
                            remote: "This role name is already taken."
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

                // Module checkbox "Select All" functionality
                $('.module-checkbox').on('change', function() {
                    var isChecked = $(this).is(':checked');
                    $(this).closest('.card').find('.permission-checkbox').prop('checked', isChecked);
                });

                // Individual permission checkbox change functionality
                $('.permission-checkbox').on('change', function() {
                    var $card = $(this).closest('.card');
                    var totalCheckboxes = $card.find('.permission-checkbox').length;
                    var checkedCheckboxes = $card.find('.permission-checkbox:checked').length;

                    if (totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0) {
                        $card.find('.module-checkbox').prop('checked', true);
                    } else {
                        $card.find('.module-checkbox').prop('checked', false);
                    }
                });

                // Initialize module checkboxes on page load (for edit form)
                $('.module-checkbox').each(function() {
                    var $card = $(this).closest('.card');
                    var totalCheckboxes = $card.find('.permission-checkbox').length;
                    var checkedCheckboxes = $card.find('.permission-checkbox:checked').length;

                    if (totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes) {
                        $(this).prop('checked', true);
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>
