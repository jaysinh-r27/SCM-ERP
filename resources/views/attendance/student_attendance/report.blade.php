<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="alert-box">
                @include('layouts.alert')
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex flex-wrap align-items-center" style="gap:10px;">
                            <div>
                                <input type="text" name="list-search" class="form-control" placeholder="Search...">
                            </div>
                            <div>
                                <input type="date" name="from_date" id="from_date" class="form-control"
                                    placeholder="From Date" value="{{ $fromDate }}">
                            </div>
                            <div>
                                <input type="date" name="to_date" id="to_date" class="form-control"
                                    placeholder="To Date" value="{{ $toDate }}">
                            </div>
                            <div>
                                <select name="class_id" id="class_id" class="form-control select2">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $key => $class)
                                        <option value="{{ $key }}" {{ $class_id == $key ? 'selected' : '' }}>
                                            {{ $class }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select name="student_id" id="student_id" class="form-control select2">
                                    <option value="">Select Student</option>
                                    @foreach ($studenetData as $student)
                                        <option value="{{ $student->id }}"
                                            {{ $student_id == $student->id ? 'selected' : '' }}>{{ $student->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button class="btn btn-primary" id="filterData">Filter</button>
                            </div>
                            <div>
                                <a class="btn btn-secondary" id="ClearfilterData"
                                    href="{{ route('report.attendance') }}">Clear</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Status</th>
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
                        url: "{{ route('student.attendance.monthly.report') }}",
                        method: 'GET',
                        data: function(d) {
                            d.class_id = $('#class_id').val();
                            d.student_id = $('#student_id').val();
                            d.from_date = $('#from_date').val();
                            d.to_date = $('#to_date').val();
                            d.type = $('#type').val();
                        }
                    },
                    "columns": [{
                            "data": "DT_RowIndex",
                            "name": "DT_RowIndex",
                            "orderable": false,
                            "searchable": false
                        },
                        {
                            "data": "date",
                            "name": "date"
                        },
                        {
                            "data": "name",
                            "name": "name",
                            "orderable": false,
                            "searchable": false
                        },
                        {
                            "data": "class",
                            "name": "class",
                            "orderable": false,
                            "searchable": false
                        },
                        {
                            "data": "status",
                            "name": "status",
                            "orderable": false,
                            "searchable": false
                        }
                    ]
                });

                $('#example1_filter').hide();

                $('input[name="list-search"]').on('keyup', function() {
                    table.search(this.value).draw();
                });

                $('#filterData').on('click', function(e) {
                    e.preventDefault();
                    table.ajax.reload();
                });

                $('#ClearfilterData').on('click', function(e) {
                    e.preventDefault();
                    $('#class_id').val('');
                    $('#student_id').val('');
                    $('#from_date').val('');
                    $('#to_date').val('');
                    $('#type').val('');
                    table.ajax.reload();
                });

            });
        </script>
    @endsection
</x-app-layout>
