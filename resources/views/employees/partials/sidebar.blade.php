<nav class="sidebar">
    <div class="sidebar-header">
        <h4 class="mb-0 text-primary">
            <i class="fas fa-user-tie me-2"></i>{{ strtoupper(auth()->user()->name ?? 'Employee') }}
        </h4>
    </div>
    <div class="px-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('employee.dashboard') }}">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('services.create') }}">
                    <i class="fas fa-users me-2"></i> Service Create
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logedEmployee.showStatus') }}">
                    <i class="fas fa-chart-line me-2"></i> Change Status
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</nav>
