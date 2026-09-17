<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - FloraCharm')</title>

    <!-- Bootstrap 5 CSS & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #1e293b;
            color: #fff;
        }

        .sidebar a {
            color: #94a3b8;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar a:hover, .sidebar a.active {
            color: #fff;
            background: #334155;
            border-left: 4px solid #e85a71;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 15px 30px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar d-flex flex-column flex-shrink-0 p-3">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <i class="fa-solid fa-spa text-danger fs-4 me-2"></i>
                <span class="fs-5 fw-bold">FloraCharm Admin</span>
            </a>
            <hr class="border-secondary">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line me-2"></i> Tổng Quan (Dashboard)
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-seedling me-2"></i> Quản Lý Mẫu Hoa
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-layer-group me-2"></i> Quản Lý Danh Mục
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-receipt me-2"></i> Quản Lý Đơn Hàng
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-ticket me-2"></i> Quản Lý Voucher / Mã
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users me-2"></i> Khách Hàng (CRM)
                    </a>
                </li>
                <li class="mt-4 pt-3 border-top border-secondary">
                    <a href="{{ route('home') }}" target="_blank">
                        <i class="fa-solid fa-arrow-up-right-from-square me-2"></i> Xem Website Khách
                    </a>
                </li>
            </ul>
            <div class="mt-auto pt-3 border-top border-secondary small text-secondary">
                Đăng nhập: <strong>{{ Auth::user()->name ?? 'Admin' }}</strong>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">Đăng xuất</button>
                </form>
            </div>
        </aside>

        <!-- Main Admin Content Area -->
        <div class="flex-grow-1">
            <div class="topbar d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">@yield('page_title', 'Bảng Điều Khiển Quản Trị')</h5>
                <div>
                    <span class="badge bg-danger rounded-pill px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Quyền Quản Trị Viên</span>
                </div>
            </div>

            <div class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
