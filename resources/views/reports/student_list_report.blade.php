<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="alert-box">
                @include('layouts.alert')
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex flex-wrap align-items-center" style="gap: 10px;">
                            <div>
                                <input type="text" name="list-search" class="form-control" placeholder="Search...">
                            </div>
                            <div>
                                <select name="class_id" id="class_id" class="form-control select2">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select name="academic_session_id" id="academic_session_id"
                                    class="form-control select2">
                                    <option value="">Select Academic Session</option>
                                    @foreach ($academic_sessions as $academic_session)
                                        <option value="{{ $academic_session->id }}">{{ $academic_session->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Class</th>
                                        <th>Admission Date</th>
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
                        url: "{{ route('report.student.list') }}",
                        method: 'GET',
                        data: function(d) {
                            d.class_id = $('#class_id').val();
                            d.academic_session_id = $('#academic_session_id').val();
                        }
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
                            "data": "email",
                            "name": "email"
                        },
                        {
                            "data": "phone",
                            "name": "phone"
                        },
                        {
                            "data": "class",
                            "name": "class",
                            "orderable": false,
                            "searchable": false
                        },
                        {
                            "data": "admission_date",
                            "name": "admission_date"
                        }
                    ]
                });

                $('#example1_filter').hide();

                $('input[name="list-search"]').on('keyup', function() {
                    table.search(this.value).draw();
                });

                $(document).on('change', '#class_id, #academic_session_id', function() {
                    table.ajax.reload();
                });
            });
        </script>
    @endsection
</x-app-layout>
