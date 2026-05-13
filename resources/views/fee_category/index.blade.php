<x-app-layout>

<section class="content">
<div class="container-fluid">
    <div class="alert-box">
        @include('layouts.alert')
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center">
                    <div>
                        <input type="text" name="list-search" class="form-control" placeholder="Search...">
                    </div>
                    @can('create.fee.category')
                        <div class="float-right ml-auto">
                            <a href="{{ route('fee-category.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i>
                                Create Category
                            </a>
                        </div>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

@section('script')
<script>
    $(document).ready(function() {
        var table = $('#example1').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "responsive": true,
            "language": {
                "search": "",
                "searchPlaceholder": ""
            },
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "{{ route('fee-category.getData') }}",
                method: 'GET'
            },
            "columns": [
                { "data": "DT_RowIndex", "name": "DT_RowIndex", "orderable": false, "searchable": false },
                { "data": "name", "name": "name" },
                { "data": "amount", "name": "amount" },
                { "data": "status", "name": "status" },
                { "data": "action", "name": "action", "orderable": false, "searchable": false }
            ]
        });

        $('#example1_filter').hide();

        $('input[name="list-search"]').on('keyup', function() {
            table.search(this.value).draw();
        });
    });

    function deleteRecord(url) {
        if(confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#example1').DataTable().ajax.reload();
                }
            });
        }
    }
</script>
@endsection
</x-app-layout>
