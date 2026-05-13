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
                                <select name="exam_id" id="exam_id" class="form-control select2">
                                    <option value="">Select Exam</option>
                                    @foreach ($exams as $exam)
                                        <option value="{{ $exam->id }}">{{ $exam->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select name="status" id="status" class="form-control select2">
                                    <option value="">Select Result</option>
                                    <option value="1">Pass</option>
                                    <option value="0">Fail</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student Name</th>
                                        <th>Exam Name</th>
                                        <th>Class</th>
                                        <th>Total Marks</th>
                                        <th>Obtained Marks</th>
                                        <th>Percentage</th>
                                        <th>Grade</th>
                                        <th>Result</th>
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
                        url: "{{ route('report.exam.result') }}",
                        method: 'GET',
                        data: function(d) {
                            d.class_id = $('#class_id').val();
                            d.exam_id = $('#exam_id').val();
                            d.status = $('#status').val();
                        }
                    },
                    "columns": [{
                            "data": "DT_RowIndex",
                            "name": "DT_RowIndex",
                            "orderable": false,
                            "searchable": false
                        },
                        {
                            "data": "student_id",
                            "name": "student_id"
                        },
                        {
                            "data": "exam_id",
                            "name": "exam_id"
                        },
                        {
                            "data": "class",
                            "name": "class",
                            "orderable": false,
                            "searchable": false
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
                            "name": "percentage"
                        },
                        {
                            "data": "grade",
                            "name": "grade"
                        },
                        {
                            "data": "status",
                            "name": "status"
                        }
                    ]
                });

                $('#example1_filter').hide();

                $('input[name="list-search"]').on('keyup', function() {
                    table.search(this.value).draw();
                });

                $(document).on('change', '#class_id, #exam_id, #status', function() {
                    table.ajax.reload();
                });
            });
        </script>
    @endsection
</x-app-layout>
