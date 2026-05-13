<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="alert-box">
                @include('layouts.alert')
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="card p-4 shadow-sm">
                                    <h4 class="mb-3">Date : {{ now()->format('d M Y') }}</h4>

                                    <form action="{{ route('student.attendance.store') }}" method="POST" class="mb-4"
                                        id="attendanceForm">
                                        @csrf


                                        @if (empty($todayAttendance) || empty($todayAttendance->in_time))
                                            <button type="submit" name="type" value="checkin"
                                                class="btn btn-success px-4">
                                                Check In <i class="ti ti-login-2 ms-1"></i>
                                            </button>
                                        @elseif(!empty($todayAttendance->in_time) && empty($todayAttendance->out_time))
                                            <button type="submit" name="type" value="checkout"
                                                class="btn btn-danger px-4">
                                                Check Out <i class="ti ti-logout-2 ms-1"></i>
                                            </button>
                                        @else
                                            <button type="submit" name="type" value="checkin"
                                                class="btn btn-success px-4">
                                                Check In <i class="ti ti-login-2 ms-1"></i>
                                            </button>
                                        @endif
                                    </form>

                                    {{-- HISTORY TABLE (LOOP HERE) --}}
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Check In</th>
                                                    <th>Check Out</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($attendances))
                                                    @forelse($attendances as $key => $attendance)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td class="text-success">
                                                                {{ !empty($attendance->in_time) ? $attendance->in_time->format('h:i:s A') : '-' }}
                                                            </td>
                                                            <td class="text-danger">
                                                                {{ !empty($attendance->out_time) ? $attendance->out_time->format('h:i:s A') : '-' }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4">No Attendance Records</td>
                                                        </tr>
                                                    @endforelse
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

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
                $('#attendanceForm').on('submit', function(e) {
                    e.preventDefault();

                    let form = this;

                    let confirmAction = confirm('Are you sure you want to Check In/Out?');

                    if (confirmAction) {
                        form.submit();
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>
