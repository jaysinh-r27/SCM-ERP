@if (session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5 class="mb-0"><i class="icon fas fa-check"></i> Success! <span id="success-message">{{ session('success') }}</span> </h5>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5 class="mb-0"><i class="icon fas fa-ban"></i> Error! <span id="error-message">{{ session('error') }}</span> </h5>
    </div>
@endif
