<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Aytun Film AI')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom Admin CSS -->
    <style>
        :root {
            --sidebar-width: 250px;
            --navbar-height: 60px;
        }

        body {
            font-size: 0.9rem;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .sidebar-brand {
            padding: 1.5rem 1rem;
            font-size: 1.3rem;
            font-weight: bold;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-decoration: none;
            color: white;
            display: block;
        }

        .sidebar-brand:hover {
            color: #60a5fa;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-menu-item {
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: #60a5fa;
        }

        .sidebar-menu-item.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left-color: #60a5fa;
        }

        .sidebar-menu-item i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            width: 20px;
        }

        .sidebar-menu-item .badge {
            margin-left: auto;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .admin-navbar {
            height: var(--navbar-height);
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
            background: #f9fafb;
        }

        /* Mobile Toggle */
        .sidebar-toggle {
            display: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }
        }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <i class="bi bi-shield-check"></i> Admin Panel
        </a>

        <nav class="sidebar-menu">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <!-- Talepler -->
            <a href="{{ route('admin.requests.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.requests.*') ? 'active' : '' }}">
                <i class="bi bi-film"></i>
                <span>Talepler</span>
                @php
                    $pendingCount = \App\Models\Request::pending()->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="badge bg-danger rounded-pill">{{ $pendingCount }}</span>
                @endif
            </a>

            <!-- Kullanıcılar -->
            <a href="{{ route('admin.users.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Kullanıcılar</span>
            </a>

            <!-- Admin Kullanıcılar -->
            <a href="{{ route('admin.admins.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i>
                <span>Admin Kullanıcılar</span>
            </a>

            <!-- Paketler -->
            <a href="{{ route('admin.packages.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>Paketler</span>
            </a>

            <!-- İndirim Kuponları -->
            <a href="{{ route('admin.coupons.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated"></i>
                <span>İndirim Kuponları</span>
            </a>

            <!-- Siparişler -->
            <a href="{{ route('admin.orders.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart-check"></i>
                <span>Siparişler</span>
            </a>

            <!-- İstatistikler -->
            <a href="{{ route('admin.statistics.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.statistics.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i>
                <span>İstatistikler</span>
            </a>

            <hr style="border-color: rgba(255,255,255,0.1); margin: 1rem 0;">

            <!-- Makale Kategorileri -->
            <a href="{{ route('admin.article-categories.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.article-categories.*') ? 'active' : '' }}">
                <i class="bi bi-folder"></i>
                <span>Makale Kategorileri</span>
            </a>

            <!-- Makaleler -->
            <a href="{{ route('admin.articles.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                <i class="bi bi-newspaper"></i>
                <span>Makaleler</span>
            </a>

            <hr style="border-color: rgba(255,255,255,0.1); margin: 1rem 0;">

            <!-- Ana Sayfa Yönetimi -->
            <a href="{{ route('admin.home.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.home.*') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i>
                <span>Ana Sayfa Yönetimi</span>
            </a>

            <!-- Siteye Dön -->
            <a href="{{ route('home') }}" class="sidebar-menu-item">
                <i class="bi bi-arrow-left-circle"></i>
                <span>Siteye Dön</span>
            </a>
        </nav>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <nav class="admin-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-link sidebar-toggle me-3" onclick="toggleSidebar()">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
            </div>

            <div class="d-flex align-items-center">
                <span class="me-3">
                    <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                </span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Çıkış
                    </button>
                </form>
            </div>
        </nav>

        <!-- Flash Mesajlar -->
        @if(session('success'))
            <div class="px-4 pt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="px-4 pt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.sidebar-toggle');

            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
