<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Founder</div>

                <a class="nav-link {{ request()->routeIs('founder.dashboard') ? 'active' : '' }}" href="{{ route('founder.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>


                <a class="nav-link {{ request()->routeIs('companies.index') ? 'active' : '' }}" href="{{ route('companies.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                    Company Creation
                </a>

                <a class="nav-link {{ request()->routeIs('superadmins.index') ? 'active' : '' }}" href="{{ route('superadmins.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    SuperAdmin Details
                </a>

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

            <form id="logout-form" action="" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </nav>
</div>
