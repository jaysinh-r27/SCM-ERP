<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="alert-box">
                @include('layouts.alert')
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        {{-- Filter Bar --}}
                        <div class="card-header d-flex flex-wrap align-items-center" style="gap: 10px;">

                            {{-- From Date --}}
                            <div>
                                <input type="date" id="from_date" class="form-control"
                                    placeholder="From Date" value="{{ $fromDate }}">
                            </div>

                            {{-- To Date --}}
                            <div>
                                <input type="date" id="to_date" class="form-control"
                                    placeholder="To Date" value="{{ $toDate }}">
                            </div>

                            {{-- Staff Filter --}}
                            <div style="min-width: 180px;">
                                <select id="staff_id" class="form-control select2">
                                    <option value="">All Staff</option>
                                    @foreach ($staffList as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ $staffId == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Type Filter --}}
                            <div>
                                <select id="type" class="form-control select2">
                                    <option value="{{ encrypt(\App\Models\User::ROLE_STUDENT) }}"
                                        {{ !empty($type) && decrypt($type) == \App\Models\User::ROLE_STUDENT ? 'selected' : '' }}>
                                        Student
                                    </option>
                                    <option value="{{ encrypt(\App\Models\User::ROLE_STAFF) }}"
                                        {{ empty($type) || decrypt($type) == \App\Models\User::ROLE_STAFF ? 'selected' : '' }}>
                                        Staff
                                    </option>
                                </select>
                            </div>

                            {{-- Filter Button --}}
                            <div>
                                <button class="btn btn-primary" id="filterData">Filter</button>
                            </div>

                            {{-- Clear Button --}}
                            <div>
                                <a class="btn btn-secondary"
                                    href="{{ route('report.attendance') }}?type={{ encrypt(\App\Models\User::ROLE_STAFF) }}">
                                    Clear
                                </a>
                            </div>

                        </div>

                        {{-- Table --}}
                        <div class="card-body">
                            <div class="mb-2">
                                <input type="text" id="list-search" class="form-control" style="max-width: 250px;"
                                    placeholder="Search...">
                            </div>
                            <table id="staffAttendanceTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>In Time</th>
                                        <th>Out Time</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    @section('script')
        <script>
            $(document).ready(function () {

                var table = $('#staffAttendanceTable').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    lengthChange: false,
                    searching: true,
                    ordering: true,
                    info: true,
                    autoWidth: false,
                    responsive: true,
                    language: {
                        search: "",
                        searchPlaceholder: "",
                        processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
                    },
                    ajax: {
                        url: "{{ route('report.attendance') }}",
                        method: 'GET',
                        data: function (d) {
                            d.from_date = $('#from_date').val();
                            d.to_date   = $('#to_date').val();
                            d.staff_id  = $('#staff_id').val();
                            d.type      = $('#type').val();
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex',       name: 'DT_RowIndex',      orderable: false, searchable: false },
                        { data: 'staff_name',         name: 'staff_name',       orderable: false, searchable: false },
                        { data: 'display_date',       name: 'date',             orderable: true,  searchable: false },
                        { data: 'in_time_display',    name: 'in_time_display',  orderable: false, searchable: false },
                        { data: 'out_time_display',   name: 'out_time_display', orderable: false, searchable: false },
                        { data: 'duration',           name: 'duration',         orderable: false, searchable: false },
                        { data: 'status',             name: 'status',           orderable: false, searchable: false }
                    ]
                });

                // Hide built-in search box; use custom one
                $('#staffAttendanceTable_filter').hide();

                $('#list-search').on('keyup', function () {
                    table.search(this.value).draw();
                });

                $('#filterData').on('click', function (e) {
                    e.preventDefault();

                    var params = {
                        from_date : $('#from_date').val(),
                        to_date   : $('#to_date').val(),
                        staff_id  : $('#staff_id').val(),
                        type      : $('#type').val()
                    };

                    var query = Object.keys(params)
                        .map(function (k) { return k + '=' + encodeURIComponent(params[k]); })
                        .join('&');

                    window.location.href = window.location.pathname + '?' + query;
                });

            });
        </script>
    @endsection
</x-app-layout>
