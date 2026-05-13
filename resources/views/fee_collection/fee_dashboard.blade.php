<x-app-layout>

<section class="content">
<div class="container-fluid">
    <div class="alert-box">
        @include('layouts.alert')
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap align-items-center">
                    <div>
                        <input type="text" name="list-search" class="form-control" placeholder="Search...">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="example1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Total Fees</th>
                                    <th>Paid Amount</th>
                                    <th>Pending Amount</th>
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
                url: "{{ route('fee.collection.dashboard.data') }}",
                method: 'GET'
            },
            "columns": [
                { "data": "DT_RowIndex", "name": "DT_RowIndex", "orderable": false, "searchable": false },
                { "data": "name", "name": "name" },
                { "data": "email", "name": "email" },
                { "data": "total_fees", "name": "total_fees", "searchable": false, "orderable": false },
                { "data": "paid_amount", "name": "paid_amount", "searchable": false, "orderable": false },
                { "data": "pending_amount", "name": "pending_amount", "searchable": false, "orderable": false },
                { "data": "action", "name": "action", "orderable": false, "searchable": false }
            ]
        });

        $('#example1_filter').hide();

        $('input[name="list-search"]').on('keyup', function() {
            table.search(this.value).draw();
        });
    });
</script>
@endsection
</x-app-layout>
