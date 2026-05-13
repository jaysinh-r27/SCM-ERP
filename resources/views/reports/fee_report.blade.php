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
                                <select name="fee_cat_id" id="fee_cat_id" class="form-control select2">
                                    <option value="">Select Fee Category</option>
                                    @foreach ($feecats as $key => $feecat)
                                        <option value="{{ $key }}">{{ $feecat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select name="class_id" id="class_id" class="form-control select2">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $key => $class)
                                        <option value="{{ $key }}">{{ $class }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select name="student_id" id="student_id" class="form-control select2">
                                    <option value="">Select Student</option>
                                    @foreach ($students as $key => $student)
                                        <option value="{{ $key }}">{{ $student }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select name="status" id="status" class="form-control select2">
                                    <option value="">Select Status</option>
                                    <option value="paid">Paid</option>
                                    <option value="partial">Partial</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student Name</th>
                                        <th>Class</th>
                                        <th>Fee Category</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Remaining Amount</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Payment Method</th>
                                        <th>Payment Status</th>
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
                        url: "{{ route('report.fee') }}",
                        method: 'GET',
                        data: function(d) {
                            d.class_id = $('#class_id').val();
                            d.student_id = $('#student_id').val();
                            d.status = $('#status').val();
                            d.fee_cat_id = $('#fee_cat_id').val();
                        }
                    },
                    "columns": [{
                            "data": "DT_RowIndex",
                            "name": "DT_RowIndex",
                            "orderable": false,
                            "searchable": false
                        },
                        {
                            "data": "student_name",
                            "name": "student_name"
                        },
                        {
                            "data": "class",
                            "name": "class",
                            "orderable": false,
                            "searchable": false
                        },
                        {
                            "data": "fee_category",
                            "name": "fee_categoryv",
                            "orderable": false,
                            "searchable": false
                        },
                        {
                            "data": "total_amount",
                            "name": "total_amount"
                        },
                        {
                            "data": "paid_amount",
                            "name": "paid_amount"
                        },
                        {
                            "data": "remaining_amount",
                            "name": "remaining_amount"
                        },
                        {
                            "data": "due_date",
                            "name": "due_date"
                        },
                        {
                            "data": "status",
                            "name": "status"
                        },
                        {
                            "data": "payment_method",
                            "name": "payment_method",
                            "orderable": false,
                            "searchable": false
                        },
                        {
                            "data": "payments_receipts",
                            "name": "payments_receipts",
                            "orderable": false,
                            "searchable": false
                        }
                    ]
                });

                $('#example1_filter').hide();

                $('input[name="list-search"]').on('keyup', function() {
                    table.search(this.value).draw();
                });

                $(document).on('change', '#class_id, #student_id, #fee_cat_id, #status', function() {
                    table.ajax.reload();
                });
            });
        </script>
    @endsection
</x-app-layout>
