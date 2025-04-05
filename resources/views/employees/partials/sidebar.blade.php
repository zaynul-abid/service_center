<nav class="sidebar bg-white shadow-sm">
    <div class="sidebar-header p-3 border-bottom">
        <h4 class="mb-0 text-primary">
            <i class="fas fa-user-tie me-2"></i>EMPLOYEE
        </h4>
    </div>
    <div class="px-3 py-2">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('employee.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('services.create') }}">
                    <i class="fas fa-plus-circle me-2"></i> Service Create
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logedEmployee.showStatus') }}">
                    <i class="fas fa-exchange-alt me-2"></i> Change Status
                </a>
            </li>
            <li class="nav-item mt-2 border-top pt-2">
                <a class="nav-link text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</nav>
