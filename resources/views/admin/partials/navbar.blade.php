<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Admin</div>

                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <a class="nav-link {{ request()->routeIs('services.index') ? 'active' : '' }}" href="{{ route('services.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                    Services
                </a>

                <a class="nav-link {{ request()->routeIs('show.assign') ? 'active' : '' }}" href="{{ route('show.assign') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                    Assign
                </a>

                <a class="nav-link {{ request()->routeIs('service.followups') ? 'active' : '' }}" href="{{ route('service.followups') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Follow Ups
                </a>


                <a class="nav-link collapsed {{ request()->routeIs('admin.enquiry.report') || request()->routeIs('admin.employee.report') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#reportMenu" aria-expanded="false">
                    <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                    Report
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>


                <div class="collapse {{ request()->routeIs('admin.service.report') || request()->routeIs('admin.service.report') ? 'show' : '' }}" id="reportMenu">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->routeIs('admin.service.report') ? 'active' : '' }}" href="{{ route('admin.service.report') }}">Service Report</a>
                        <a class="nav-link {{ request()->routeIs('admin.employee.report') ? 'active' : '' }}" href="{{ route('admin.employee.report') }}">Employee Report</a>
                    </nav>
                </div>



                {{--

                <a class="nav-link {{ request()->routeIs('enquiry_users.users') ? 'active' : '' }}" href="">
                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                    Users List
                </a>

                <a class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}" href="">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                    Dashboard
                </a>

                <a class="nav-link {{ request()->routeIs('admin.assign') ? 'active' : '' }}" href="">
                    <div class="sb-nav-link-icon"><i class="fas fa-tasks"></i></div>
                    Assign Enquiries
                </a>

                <a class="nav-link {{ request()->routeIs('admin.follow-ups') ? 'active' : '' }}" href="">
                    <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                    Follow Ups
                </a>

                <a class="nav-link collapsed {{ request()->routeIs('employees.index') || request()->routeIs('departments.index') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#employeeMenu" aria-expanded="false">
                    <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>
                    Employee
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ request()->routeIs('employees.index') || request()->routeIs('departments.index') ? 'show' : '' }}" id="employeeMenu">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->routeIs('employees.index') ? 'active' : '' }}" href="">Employee Details</a>
                        <a class="nav-link {{ request()->routeIs('departments.index') ? 'active' : '' }}" href="">Department</a>
                    </nav>
                </div>

                <a class="nav-link collapsed {{ request()->routeIs('admin.enquiry.report') || request()->routeIs('admin.employee.report') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#reportMenu" aria-expanded="false">
                    <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                    Report
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ request()->routeIs('admin.enquiry.report') || request()->routeIs('admin.employee.report') ? 'show' : '' }}" id="reportMenu">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link {{ request()->routeIs('admin.enquiry.report') ? 'active' : '' }}" href="{{ route('admin.enquiry.report') }}">Enquiry Report</a>
                        <a class="nav-link {{ request()->routeIs('admin.employee.report') ? 'active' : '' }}" href="{{ route('admin.employee.report') }}">Employee Report</a>
                    </nav>
                </div> --}}
            </div>
        </div>

        <!-- Sidebar Footer with Logout -->
        <div class="sb-sidenav-footer">
            {{-- <div class="small">Logged in as: <strong>{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</strong></div> --}}

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </nav>
</div>
