{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SmartERP') - SmartERP</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-blue: #1e3a8a;
            --primary-orange: #f97316;
            --sidebar-width: 280px;
            --navbar-height: 60px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-blue) 0%, #1e40af 100%);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: white;
        }

        .sidebar-header small {
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
        }

        .sidebar-nav {
            padding: 15px 0;
        }

        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            margin: 2px 15px;
        }

        .sidebar-nav .nav-link i {
            margin-right: 10px;
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar-nav .nav-link.active {
            background: var(--primary-orange);
            color: white;
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);
        }

        .sidebar-nav .nav-section {
            color: rgba(255, 255, 255, 0.5);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 20px 15px 10px;
            font-weight: 600;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Navbar Styles */
        .navbar-top {
            background: white;
            height: var(--navbar-height);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            padding: 0 25px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar-top .navbar-brand {
            display: none;
            color: var(--primary-blue);
            font-weight: 700;
            font-size: 20px;
        }

        .navbar-top .nav-link {
            color: #64748b;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .navbar-top .nav-link:hover {
            background: #f1f5f9;
            color: var(--primary-blue);
        }

        /* Page Content */
        .page-content {
            padding: 25px;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .card:hover {
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 12px 12px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
            color: var(--primary-blue);
        }

        /* Stat Cards */
        .stat-card {
            border-radius: 12px;
            padding: 20px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 0;
        }

        .stat-card .icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 48px;
            opacity: 0.2;
        }

        .stat-card h3 {
            font-size: 28px;
            font-weight: 700;
            margin: 10px 0;
        }

        .stat-card p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .stat-card.blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .stat-card.orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }

        .stat-card.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .stat-card.purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        }

        /* Table Styles */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            color: var(--primary-blue);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            vertical-align: middle;
            font-size: 14px;
        }

        /* Badge Styles */
        .badge {
            padding: 5px 10px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 12px;
        }

        /* Button Styles */
        .btn {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .btn-primary:hover {
            background: #1e40af;
            border-color: #1e40af;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }

        .btn-warning {
            background: var(--primary-orange);
            border-color: var(--primary-orange);
            color: white;
        }

        .btn-warning:hover {
            background: #ea580c;
            border-color: #ea580c;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }

        /* Form Styles */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 10px 15px;
            font-size: 14px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.15);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .navbar-top .navbar-brand {
                display: block;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.5s ease forwards;
        }

        /* Alert styles */
        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>SmartERP</h3>
            <small>Enterprise Solution</small>
        </div>

        <nav class="sidebar-nav">
            <!-- Dashboard - Visible to all -->
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <!-- ==================== MY PROFILE (All Users) ==================== -->
            <div class="nav-section">My Profile</div>
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person"></i> My Profile
            </a>
            <a href="{{ route('payslips.my') }}" class="nav-link {{ request()->routeIs('payslips.my') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> My Payslips
            </a>

            <!-- ==================== HR MANAGEMENT ==================== -->
            @can('view employees')
            <div class="nav-section">HR Management</div>
            <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Employees
            </a>
            @endcan

            @can('view departments')
            <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3"></i> Departments
            </a>
            @endcan

            @can('view positions')
            <a href="{{ route('positions.index') }}" class="nav-link {{ request()->routeIs('positions.*') ? 'active' : '' }}">
                <i class="bi bi-briefcase"></i> Positions
            </a>
            @endcan

            @can('view all attendance')
            <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i> Attendance
            </a>
            @endcan

            @can('view own attendance')
                @cannot('view all attendance')
                <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> My Attendance
                </a>
                @endcannot
            @endcan

            @can('view all leave requests')
            <a href="{{ route('leave-requests.index') }}" class="nav-link {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-x"></i> Leave Requests
            </a>
            @endcan

            @can('view own leave requests')
                @cannot('view all leave requests')
                <a href="{{ route('leave-requests.index') }}" class="nav-link {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-x"></i> My Leave
                </a>
                @endcannot
            @endcan

            @can('view employees')
            <a href="{{ route('hr.recruitments.index') }}" class="nav-link {{ request()->routeIs('hr.recruitments.*') ? 'active' : '' }}">
                <i class="bi bi-person-plus"></i> Recruitment
            </a>
            <a href="{{ route('hr.performance.index') }}" class="nav-link {{ request()->routeIs('hr.performance.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i> Performance
            </a>
            @endcan
            @can('view employees')
            <a href="{{ route('id-cards.index') }}" class="nav-link {{ request()->routeIs('id-cards.*') ? 'active' : '' }}">
                <i class="bi bi-id-card"></i> ID Cards
            </a>
            @endcan

            <!-- ==================== INVENTORY ==================== -->
            @can('view products')
            <div class="nav-section">Inventory</div>
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="bi bi-box"></i> Products
            </a>
            @endcan

            @can('view categories')
            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Categories
            </a>
            @endcan

            @can('view suppliers')
            <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i> Suppliers
            </a>
            @endcan

            @can('view warehouses')
            <a href="{{ route('warehouses.index') }}" class="nav-link {{ request()->routeIs('warehouses.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Warehouses
            </a>
            @endcan

            @can('view stock movements')
            <a href="{{ route('stock-movements.index') }}" class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-left-right"></i> Stock Movements
            </a>
            @endcan

            <!-- ==================== SALES ==================== -->
            @can('view customers')
            <div class="nav-section">Sales</div>
            <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i> Customers
            </a>
            @endcan

            @can('view invoices')
            <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Invoices
            </a>
            @endcan

            @can('view payments')
            <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                <i class="bi bi-cash"></i> Payments
            </a>
            @endcan

            <!-- ==================== ACCOUNTING ==================== -->
            @can('view expenses')
            <div class="nav-section">Accounting</div>
            <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                <i class="bi bi-cash-coin"></i> Expenses
            </a>
            @endcan
            @can('view employees')
            <div class="nav-section">Payroll</div>
            <a href="{{ route('payslips.index') }}" class="nav-link {{ request()->routeIs('payslips.*') ? 'active' : '' }}">
                <i class="bi bi-currency-dollar"></i> Payroll
            </a>
            @endcan

            <!-- ==================== CRM ==================== -->
            @can('view leads')
            <div class="nav-section">CRM</div>
            <a href="{{ route('leads.index') }}" class="nav-link {{ request()->routeIs('leads.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i> Leads
            </a>
            @endcan

            <!-- ==================== PROJECTS ==================== -->
            @can('view projects')
            <div class="nav-section">Projects</div>
            <a href="{{ route('projects.index') }}" class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                <i class="bi bi-kanban"></i> Projects
            </a>
            @endcan

            <!-- ==================== REPORTS ==================== -->
            @can('view reports')
            <div class="nav-section">Reports</div>
            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-bar-graph"></i> Reports
            </a>
            @endcan

            <!-- ==================== SYSTEM (Admin Only) ==================== -->
            @can('view settings')
            <div class="nav-section">System</div>
            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> Settings
            </a>
            @endcan

            @can('manage users')
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i> Users & Roles
            </a>
            @endcan

            @can('create users')
            <a href="{{ route('register') }}" class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}">
                <i class="bi bi-person-plus"></i> Add Employee
            </a>
            @endcan

            @can('view activity logs')
            <a href="{{ route('activity-logs.index') }}" class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Activity Logs
            </a>
            @endcan
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <nav class="navbar-top">
            <button class="btn btn-link d-md-none" onclick="toggleSidebar()">
                <i class="bi bi-list" style="font-size: 24px;"></i>
            </button>
            <span class="navbar-brand">SmartERP</span>
            
            <div class="ms-auto d-flex align-items-center">
                <!-- Notifications -->
                <div class="dropdown me-2">
                    <button class="nav-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        @if(auth()->check())
                            @php
                                $unreadNotifications = auth()->user()->notifications()->where('is_read', false)->count();
                            @endphp
                            @if($unreadNotifications > 0)
                                <span class="badge bg-danger rounded-pill" style="font-size: 10px;">
                                    {{ $unreadNotifications }}
                                </span>
                            @endif
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                        <div class="dropdown-header">Notifications</div>
                        @if(auth()->check())
                            @php
                                $notifications = auth()->user()->notifications()->latest()->take(5)->get();
                            @endphp
                            @forelse($notifications as $notification)
                                <a href="#" class="dropdown-item">
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    <br>{{ $notification->message }}
                                </a>
                            @empty
                                <div class="dropdown-item text-muted">No notifications</div>
                            @endforelse
                        @endif
                    </div>
                </div>

                <!-- User Menu -->
                <div class="dropdown">
                    <button class="nav-link dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                             style="width: 35px; height: 35px; font-size: 14px;">
                            {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'U' }}
                        </div>
                        <span class="d-none d-md-inline">{{ auth()->check() ? auth()->user()->name : 'User' }}</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-header">{{ auth()->check() ? auth()->user()->email : '' }}</div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="bi bi-person me-2"></i> Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);

        // Confirmation dialog helper
        function confirmDelete(form) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1e3a8a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // Toast notification helper
        function showToast(message, type = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            Toast.fire({
                icon: type,
                title: message
            });
        }
    </script>

    @stack('scripts')
</body>
</html>