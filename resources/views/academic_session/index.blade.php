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
                            @can('create.academic.session')
                                <div class="float-right ml-auto">
                                    <a href="{{ route('academic.session.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i>
                                        Create Academic Session
                                    </a>
                                </div>
                            @endcan
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
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
                        url: "{{ route('academic.session.getData') }}",
                        method: 'GET'
                    },
                    "columns": [{
                            "data": "DT_RowIndex",
                            "name": "DT_RowIndex",
                            "orderable": false,
                            "searchable": false
                        },
                        {
                            "data": "name",
                            "name": "name"
                        },
                        {
                            "data": "start_date",
                            "name": "start_date"
                        },
                        {
                            "data": "end_date",
                            "name": "end_date"
                        },
                        {
                            "data": "status",
                            "name": "status"
                        },
                        {
                            "data": "action",
                            "name": "action",
                            "orderable": false,
                            "searchable": false
                        }
                    ]
                });

                $('#example1_filter').hide();

                $('input[name="list-search"]').on('keyup', function() {
                    table.search(this.value).draw();
                });

                $(document).on('click', '.delete-record', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to delete this record?')) {
                        var url = $(this).data('url');
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                table.ajax.reload(null, false);

                                var alertHtml =
                                    '<div class="alert alert-success alert-dismissible">' +
                                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                    '<h5 class="mb-0"><i class="icon fas fa-check"></i> Success! <span id="success-message">' +
                                    response.success + '</span> </h5>' +
                                    '</div>';
                                $('.alert-box').html(alertHtml);
                            },
                            error: function(xhr) {
                                var errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr
                                    .responseJSON.message : 'Failed to delete record.';
                                var alertHtml =
                                    '<div class="alert alert-danger alert-dismissible">' +
                                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                    '<h5 class="mb-0"><i class="icon fas fa-ban"></i> Error! <span id="error-message">' +
                                    errorMsg + '</span> </h5>' +
                                    '</div>';
                                $('.alert-box').html(alertHtml);
                            }
                        });
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>
