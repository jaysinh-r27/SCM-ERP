<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info mt-3">
                        <div class="card-header">
                            <h3 class="card-title">View Role Details</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" value="{{ $role->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Slug</label>
                                        <input type="text" class="form-control" value="{{ $role->slug }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" rows="3" readonly>{{ $role->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <div class="mt-2">
                                            <div class="custom-control custom-radio d-inline mr-3">
                                                <input class="custom-control-input" type="radio" id="statusActive"
                                                    value="1" {{ $role->status == 1 ? 'checked' : '' }} disabled>
                                                <label for="statusActive" class="custom-control-label">Active</label>
                                            </div>
                                            <div class="custom-control custom-radio d-inline">
                                                <input class="custom-control-input" type="radio" id="statusInactive"
                                                    value="0" {{ $role->status == 0 ? 'checked' : '' }} disabled>
                                                <label for="statusInactive"
                                                    class="custom-control-label">Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                    <div class="form-group">
                                        <label>Permissions</label>
                                        <div class="row">
                                            @foreach ($permissions as $moduleName => $modulePermissions)
                                                <div class="col-md-3 mb-3">
                                                    <div class="card card-outline card-primary h-100">
                                                        <div class="card-header">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox"
                                                                    class="custom-control-input"
                                                                    id="module_{{ \Illuminate\Support\Str::slug($moduleName) }}"
                                                                    disabled
                                                                    {{ count($modulePermissions) > 0 && $modulePermissions->every(fn($p) => $role->permissions->contains('permission_id', $p->id)) ? 'checked' : '' }}>
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
                                                                    <input type="checkbox"
                                                                        class="form-check-input"
                                                                        id="permission_{{ $permission->id }}"
                                                                        disabled
                                                                        {{ $role->permissions->contains('permission_id', $permission->id) ? 'checked' : '' }}>
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <a href="{{ route('role.index') }}" class="btn btn-default"><i
                                    class="fas fa-arrow-left"></i> Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
