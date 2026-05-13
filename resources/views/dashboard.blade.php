<x-app-layout>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                @canany(['view.staff', 'view.student.admission', 'view.fee.dashboard'])

                    @can('view.student.admission')
                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $student_count }}</h3>

                                    <p>Total Students</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="{{ route('student.admission.management.index') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endcan

                    @can('view.staff')
                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $staff_count }}</h3>

                                    <p>Total Staff</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="{{ route('staff.index') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endcan

                    @can('view.fee.dashboard')
                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $pending_fee_amount }}</h3>

                                    <p>Pending Fee Amount</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="{{ route('fee.collection.dashboard') }}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endcan
                @endcanany

            </div>
        </div>
    </section>
</x-app-layout>
