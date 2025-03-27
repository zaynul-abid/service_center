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


                <a class="nav-link {{ request()->routeIs('services.index') ? 'active' : '' }}" href="{{ route('services.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                    Services
                </a>


                <a class="nav-link {{ request()->routeIs('superadmin-admins.index') ? 'active' : '' }}" href="{{ route('superadmin-admins.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tasks"></i></div>
                    Admins
                </a>

                <a class="nav-link {{ request()->routeIs('employees.index') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                    Employees
                </a>

                <a class="nav-link {{ request()->routeIs('departments.index') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                    Departments
                </a>

                <a class="nav-link {{ request()->routeIs('service.followups') ? 'active' : '' }}" href="{{ route('service.followups') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Follow Ups
                </a>

                <a class="nav-link {{ request()->routeIs('show.assign') ? 'active' : '' }}" href="{{ route('show.assign') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                    Assign
                </a>

                <a class="nav-link {{ request()->routeIs('softdelete.index') ? 'active' : '' }}" href="{{ route('softdelete.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                    Soft Deletes
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
