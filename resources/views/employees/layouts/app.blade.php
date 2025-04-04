<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee @yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="{{ asset('employee_assets/css/styles.css') }}" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            background-color: #f4f6f9;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .sidebar {
            width: 260px;
            background: white;
            box-shadow: 0 0 35px rgba(94, 94, 94, 0.1);
            transition: all 0.3s ease;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
        }

        .nav-link {
            color: var(--dark-color);
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .nav-link:hover, .nav-link.active {
            background: var(--primary-color);
            color: white !important;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            transition: all 0.3s;
            margin-left: 150px;
            width: calc(100% - 260px);
        }

        .main-content.collapsed {
            margin-left: 0;
            width: 100%;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            transition: transform 0.3s;
            border: none;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        /* Mobile sidebar toggle button */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1100;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .sidebar-toggle {
                display: flex;
            }
        }

        /* Image Modal Styles */
        .modal-image {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
        }

        .thumbnail-img {
            transition: transform 0.2s;
            cursor: pointer;
            width: 80px;
            height: 50px;
            object-fit: cover;
        }

        .thumbnail-img:hover {
            transform: scale(1.05);
        }

        #imageModal .modal-content {
            background: rgba(0, 0, 0, 0.9);
        }

        #imageModal .btn-close {
            filter: invert(1);
            opacity: 0.8;
            position: absolute;
            right: 20px;
            top: 20px;
            z-index: 1001;
        }

        /* Table styles */
        .service-table {
            border-collapse: separate;
            border-spacing: 0 15px;
        }

        .service-table thead {
            background: linear-gradient(45deg, #6c5ce7, #a8a4e6);
            color: white;
        }

        .service-table thead th {
            border: none;
            padding: 15px;
            font-size: 0.9rem;
        }

        .service-table tbody tr {
            background: white;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .service-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .service-table tbody td {
            border: none;
            padding: 15px;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-accepted { background: #e3f2fd; color: #1976d2; }
        .status-in_progress { background: #fff3e0; color: #ef6c00; }
        .status-completed { background: #e8f5e9; color: #2e7d32; }
        .status-rejected { background: #ffebee; color: #c62828; }

        .notes-textarea {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 8px;
            font-size: 0.85rem;
            transition: border-color 0.3s ease;
        }

        .notes-textarea:focus {
            border-color: #6c5ce7;
            outline: none;
        }

        .update-btn {
            padding: 6px 18px;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            background: #6c5ce7;
            border: none;
        }

        .update-btn:hover {
            background: #5b4bc4;
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(108,92,231,0.3);
        }
    </style>
</head>
<body>
<!-- Mobile Sidebar Toggle Button -->
<button class="sidebar-toggle" id="mobileSidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    @include('employees.partials.sidebar')

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        @yield('content')
    </div>
</div>

<!-- Image Modal -->
@include('employees.partials.imagemodel')

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="{{ asset('employee_assets/js/scripts.js') }}"></script>

<script>
    // Toggle sidebar on mobile
    document.getElementById('mobileSidebarToggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('show');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.getElementById('mobileSidebarToggle');

        if (window.innerWidth <= 992 &&
            !sidebar.contains(event.target) &&
            event.target !== toggleBtn &&
            !toggleBtn.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    });

    // Image Modal Function
    function updateModalImage(src) {
        document.getElementById('modalImage').src = src;
    }

    // Active Nav Links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');

            // Close sidebar on mobile after navigation
            if (window.innerWidth <= 992) {
                document.querySelector('.sidebar').classList.remove('show');
            }
        });
    });

    // Search Functionality
    const searchInput = document.querySelector('input[type="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }
</script>
</body>
</html>
