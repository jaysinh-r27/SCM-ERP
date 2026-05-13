<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }} ( {{ Auth::user()->role->name }})</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                @can('view.user')
                    <li class="nav-item">
                        <a href="{{ route('user.index') }}"
                            class="nav-link {{ request()->routeIs('user*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>
                                User Management
                            </p>
                        </a>
                    </li>
                @endcan

                @can('view.role')
                    <li class="nav-item">
                        <a href="{{ route('role.index') }}"
                            class="nav-link {{ request()->routeIs('role*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-key"></i>
                            <p>
                                Role & Permission
                            </p>
                        </a>
                    </li>
                @endcan

                @can('view.student.admission')
                    <li class="nav-item">
                        <a href="{{ route('student.admission.management.index') }}"
                            class="nav-link {{ request()->routeIs('student.admission.management*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Student Admissions
                            </p>
                        </a>
                    </li>
                @endcan

                @canany(['view.section', 'view.subject', 'view.class', 'view.academic.session'])
                    <li
                        class="nav-item {{ request()->routeIs('section*') || request()->routeIs('subject*') || request()->routeIs('student.class*') || request()->routeIs('academic.session*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs('section*') || request()->routeIs('subject*') || request()->routeIs('student.class*') || request()->routeIs('academic.session*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>
                                Academic Management
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            style="display: {{ request()->routeIs('section*') || request()->routeIs('subject*') || request()->routeIs('student.class*') || request()->routeIs('academic.session*') ? 'block' : 'none' }};">

                            @can('view.class')
                                <li class="nav-item">
                                    <a href="{{ route('student.class.index') }}"
                                        class="nav-link {{ request()->routeIs('student.class*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Class Management</p>
                                    </a>
                                </li>
                            @endcan

                            @can('view.section')
                                <li class="nav-item">
                                    <a href="{{ route('section.index') }}"
                                        class="nav-link {{ request()->routeIs('section*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Section Management</p>
                                    </a>
                                </li>
                            @endcan

                            @can('view.subject')
                                <li class="nav-item">
                                    <a href="{{ route('subject.index') }}"
                                        class="nav-link {{ request()->routeIs('subject*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Subject Management</p>
                                    </a>
                                </li>
                            @endcan

                            @can('view.academic.session')
                                <li class="nav-item">
                                    <a href="{{ route('academic.session.index') }}"
                                        class="nav-link {{ request()->routeIs('academic.session*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Academic Year Setup</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['view.student.attendance', 'view.staff.attendance'])
                    <li
                        class="nav-item 
        {{ request()->routeIs('student.attendance*') || request()->routeIs('staff.attendance*') ? 'menu-open' : '' }}">

                        <a href="#"
                            class="nav-link 
            {{ request()->routeIs('student.attendance*') || request()->routeIs('staff.attendance*') ? 'active' : '' }}">

                            <i class="nav-icon fas fa-user-check"></i>
                            <p>
                                Attendance Management
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview"
                            style="display: {{ request()->routeIs('student.attendance*') || request()->routeIs('staff.attendance*') ? 'block' : 'none' }};">

                            @can('view.student.attendance')
                                {{-- Student Attendance --}}
                                <li
                                    class="nav-item 
                    {{ request()->routeIs('student.attendance*') ? 'menu-open' : '' }}">

                                    <a href="#"
                                        class="nav-link 
                        {{ request()->routeIs('student.attendance*') ? 'active' : '' }}">

                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Student Attendance
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview"
                                        style="display: {{ request()->routeIs('student.attendance*') ? 'block' : 'none' }};">

                                        <li class="nav-item">
                                            <a href="{{ route('student.attendance.index') }}"
                                                class="nav-link {{ request()->routeIs('student.attendance.index') ? 'active' : '' }}">

                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>Attendance Entry</p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('student.attendance.list') }}"
                                                class="nav-link {{ request()->routeIs('student.attendance.list') ? 'active' : '' }}">

                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>Attendance List</p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('student.attendance.monthly.report') }}"
                                                class="nav-link {{ request()->routeIs('student.attendance.monthly.report') ? 'active' : '' }}">

                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>Monthly Report</p>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                            @endcan

                            @can('view.staff.attendance')
                                {{-- Staff Attendance --}}
                                <li
                                    class="nav-item 
                    {{ request()->routeIs('staff.attendance*') ? 'menu-open' : '' }}">

                                    <a href="#"
                                        class="nav-link 
                        {{ request()->routeIs('staff.attendance*') ? 'active' : '' }}">

                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Staff Attendance
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>

                                    <ul class="nav nav-treeview"
                                        style="display: {{ request()->routeIs('staff.attendance*') ? 'block' : 'none' }};">

                                        <li class="nav-item">
                                            <a href="{{ route('staff.attendance.index') }}"
                                                class="nav-link {{ request()->routeIs('staff.attendance.index') ? 'active' : '' }}">

                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>Attendance Entry</p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('staff.attendance.list') }}"
                                                class="nav-link {{ request()->routeIs('staff.attendance.list') ? 'active' : '' }}">

                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>Attendance List</p>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcanany

                @canany(['view.fee.dashboard', 'view.fee.category', 'assign.fee', 'collect.fee'])
                    <li
                        class="nav-item {{ request()->routeIs('fee.collection*') || request()->routeIs('fee-category*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs('fee.collection*') || request()->routeIs('fee-category*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>
                                Fee Collection
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            style="display: {{ request()->routeIs('fee.collection*') || request()->routeIs('fee-category*') ? 'block' : 'none' }};">

                            @can('view.fee.dashboard')
                                <li class="nav-item">
                                    <a href="{{ route('fee.collection.dashboard') }}"
                                        class="nav-link {{ request()->routeIs('fee.collection.dashboard') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Payment History</p>
                                    </a>
                                </li>
                            @endcan

                            @can('view.fee.category')
                                <li class="nav-item">
                                    <a href="{{ route('fee-category.index') }}"
                                        class="nav-link {{ request()->routeIs('fee-category*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Fee Categories</p>
                                    </a>
                                </li>
                            @endcan

                            @can('assign.fee')
                                <li class="nav-item">
                                    <a href="{{ route('fee.collection.assign') }}"
                                        class="nav-link {{ request()->routeIs('fee.collection.assign') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Assign Fee</p>
                                    </a>
                                </li>
                            @endcan

                            @can('collect.fee')
                                <li class="nav-item">
                                    <a href="{{ route('fee.collection.collect') }}"
                                        class="nav-link {{ request()->routeIs('fee.collection.collect') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Collect Payment</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['view.exam', 'assign.marks', 'view.results'])
                    <li
                        class="nav-item {{ request()->routeIs('exam*') || request()->routeIs('exam-result*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs('exam*') || request()->routeIs('exam-result*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>
                                Exam & Results
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            style="display: {{ request()->routeIs('exam*') || request()->routeIs('exam-result*') ? 'block' : 'none' }};">

                            @can('view.exam')
                                <li class="nav-item">
                                    <a href="{{ route('exam.index') }}"
                                        class="nav-link {{ request()->routeIs('exam.index') || request()->routeIs('exam.create') || request()->routeIs('exam.edit') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Manage Exams</p>
                                    </a>
                                </li>
                            @endcan

                            @can('assign.marks')
                                <li class="nav-item">
                                    <a href="{{ route('exam-result.marks-entry') }}"
                                        class="nav-link {{ request()->routeIs('exam-result.marks-entry') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Marks Entry</p>
                                    </a>
                                </li>
                            @endcan

                            @can('view.results')
                                <li class="nav-item">
                                    <a href="{{ route('exam-result.index') }}"
                                        class="nav-link {{ request()->routeIs('exam-result.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Exam Results</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @can('view.homework')
                    <li class="nav-item">
                        <a href="{{ route('homework.index') }}"
                            class="nav-link {{ request()->routeIs('homework*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book-open"></i>
                            <p>
                                Homework Management
                            </p>
                        </a>
                    </li>
                @endcan

                @can('view.staff')
                    <li class="nav-item">
                        <a href="{{ route('staff.index') }}"
                            class="nav-link {{ request()->routeIs('staff.index', 'staff.create', 'staff.edit', 'staff.show') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>
                                Staff Management
                            </p>
                        </a>
                    </li>
                @endcan

                @canany(['view.student.list.report', 'view.exam.result.report', 'view.fee.report',
                    'view.attendance.report'])
                    <li
                        class="nav-item {{ request()->routeIs('report.student.list*') || request()->routeIs('report.exam.result*') || request()->routeIs('report.fee*') || request()->routeIs('report.attendance*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs('report.student.list*') || request()->routeIs('report.exam.result*') || request()->routeIs('report.fee*') || request()->routeIs('report.attendance*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>
                                Reports
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            style="display: {{ request()->routeIs('report.student.list*') || request()->routeIs('report.exam.result*') || request()->routeIs('report.fee*') || request()->routeIs('report.attendance*') ? 'block' : 'none' }};">

                            @can('view.student.list.report')
                                <li class="nav-item">
                                    <a href="{{ route('report.student.list') }}"
                                        class="nav-link {{ request()->routeIs('report.student.list*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Student List Report</p>
                                    </a>
                                </li>
                            @endcan

                            @can('view.exam.result.report')
                                <li class="nav-item">
                                    <a href="{{ route('report.exam.result') }}"
                                        class="nav-link {{ request()->routeIs('report.exam.result*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Exam Result Report</p>
                                    </a>
                                </li>
                            @endcan

                            @can('view.fee.report')
                                <li class="nav-item">
                                    <a href="{{ route('report.fee') }}"
                                        class="nav-link {{ request()->routeIs('report.fee*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Fee Report</p>
                                    </a>
                                </li>
                            @endcan

                            @can('view.attendance.report')
                                <li class="nav-item">
                                    <a href="{{ route('report.attendance') }}"
                                        class="nav-link {{ request()->routeIs('report.attendance*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Attendance Report</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcanany

            </ul>
        </nav>

    </div>
</aside>
