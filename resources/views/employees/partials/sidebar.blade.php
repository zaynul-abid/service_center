<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">{{ auth()->user()->name ?? 'Employee' }}</div>

                <a class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}" href="{{ route('employee.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <a class="nav-link {{ request()->routeIs('logedEmployee.showStatus') ? 'active' : '' }}" href="{{ route('logedEmployee.showStatus') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                    Status
                </a>

                <a class="nav-link {{ request()->routeIs('services.create') ? 'active' : '' }}" href="{{ route('services.create') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div>
                    Service
                </a>
            </div>
        </div>

        <!-- Sidebar Footer with Logout -->
        <div class="sb-sidenav-footer">

            <div class="sb-sidenav-footer">
                {{-- <div class="small">Logged in as: <strong>{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</strong></div> --}}

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm w-100">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>
</div>
