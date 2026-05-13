<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="alert-box">
                @include('layouts.alert')
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Filter Results</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="filter_exam">Select Exam</label>
                                        <select class="form-control select2" id="filter_exam">
                                            <option value="">All Exams</option>
                                            @foreach ($exams as $exam)
                                                <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex flex-wrap align-items-center">
                            <div>
                                <input type="text" name="list-search" class="form-control" placeholder="Search...">
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Exam Name</th>
                                        <th>Student Name</th>
                                        <th>Class</th>
                                        <th>Total Marks</th>
                                        <th>Obtained Marks</th>
                                        <th>Percentage</th>
                                        <th>Grade</th>
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
                $('.select2').select2({
                    theme: 'bootstrap4'
                });

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
                        url: "{{ route('exam-result.getData') }}",
                        method: 'GET',
                        data: function(d) {
                            d.exam_id = $('#filter_exam').val();
                        }
                    },
                    "columns": [{
                            "data": "DT_RowIndex",
                            "name": "DT_RowIndex",
                            "orderable": false,
                            "searchable": false
                        },
                        {
                            "data": "exam_name",
                            "name": "exam.name"
                        },
                        {
                            "data": "student_name",
                            "name": "student.name"
                        },
                        {
                            "data": "class",
                            "name": "class"
                        },
                        {
                            "data": "total_marks",
                            "name": "total_marks"
                        },
                        {
                            "data": "obtained_marks",
                            "name": "obtained_marks"
                        },
                        {
                            "data": "percentage",
                            "name": "percentage",
                            render: function(data) {
                                return data + '%';
                            }
                        },
                        {
                            "data": "grade",
                            "name": "grade",
                            render: function(data) {
                                return '<span class="badge badge-info">' + data + '</span>';
                            }
                        },
                        {
                            "data": "status",
                            "name": "status",
                            render: function(data) {
                                if (data == 1) {
                                    return '<span class="badge badge-success">Pass</span>';
                                } else {
                                    return '<span class="badge badge-danger">Fail</span>';
                                }
                            }
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

                $('#filter_exam').change(function() {
                    table.draw();
                });
            });
        </script>
    @endsection
</x-app-layout>
