<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shop Hoa Tươi - Trao Gửi Yêu Thương')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary-color: #e85a71;
            --primary-hover: #d1445b;
            --secondary-color: #fcf6f5;
            --dark-color: #2b2d42;
            --text-muted: #6c757d;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--dark-color);
            background-color: #faf9f8;
        }

        h1, h2, h3, .font-serif {
            font-family: 'Playfair Display', serif;
        }

        .btn-flower {
            background-color: var(--primary-color);
            color: #fff;
            border-radius: 50px;
            padding: 10px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-flower:hover {
            background-color: var(--primary-hover);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(232, 90, 113, 0.3);
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--primary-color) !important;
        }

        .flower-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.06);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .flower-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08);
        }

        .flower-card img {
            height: 260px;
            object-fit: cover;
            width: 100%;
            transition: transform 0.5s ease;
        }

        .flower-card:hover img {
            transform: scale(1.05);
        }

        .cart-badge {
            position: absolute;
            top: -6px;
            right: -8px;
            background-color: var(--primary-color);
            color: white;
            font-size: 0.75rem;
            border-radius: 50%;
            padding: 2px 7px;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header & Navigation -->
    <header class="sticky-top bg-white shadow-sm">
        <div class="container py-2 border-bottom d-none d-md-block text-muted small">
            <div class="row align-items-center">
                <div class="col-6">
                    <i class="fa-solid fa-phone me-1 text-danger"></i> Hotline: <strong>0988.888.888</strong> (Hỗ trợ 24/7)
                    <span class="mx-2">|</span>
                    <i class="fa-solid fa-truck-fast me-1 text-danger"></i> Giao hoa hỏa tốc 60 phút
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('orders.track') }}" class="text-decoration-none text-muted me-3">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Tra cứu đơn hàng
                    </a>
                    @auth
                        <span>Xin chào, <strong>{{ Auth::user()->name }}</strong></span>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="badge bg-danger text-decoration-none ms-2">Admin</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="d-inline ms-2">
                            @csrf
                            <button type="submit" class="btn btn-link text-muted p-0 text-decoration-none small">Đăng xuất</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-decoration-none text-muted me-2"><i class="fa-regular fa-user"></i> Đăng nhập</a>
                        <a href="{{ route('register') }}" class="text-decoration-none text-muted">Đăng ký</a>
                    @endauth
                </div>
            </div>
        </div>

        <nav class="navbar navbar-expand-lg navbar-light py-3">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <i class="fa-solid fa-spa text-danger me-2"></i>FloraCharm
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-semibold">
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('home') }}">Trang Chủ</a></li>
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('flowers.index') }}">Tất Cả Hoa</a></li>
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('flowers.index', ['category' => 'hoa-sinh-nhat']) }}">Hoa Sinh Nhật</a></li>
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('flowers.index', ['category' => 'hoa-khai-truong']) }}">Hoa Khai Trương</a></li>
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('flowers.index', ['category' => 'hoa-tinh-yeu']) }}">Hoa Tình Yêu</a></li>
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('flowers.index', ['category' => 'lan-ho-diep']) }}">Lan Hồ Điệp</a></li>
                    </ul>

                    <!-- Cart Icon & Quick Action -->
                    <div class="d-flex align-items-center">
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-danger position-relative rounded-pill px-3 py-2">
                            <i class="fa-solid fa-bag-shopping"></i>
                            <span class="d-none d-md-inline ms-1">Giỏ hàng</span>
                            <span id="cartCountBadge" class="cart-badge">
                                {{ session()->has('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0 }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Thông báo Flash Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h4 class="font-serif text-white mb-3"><i class="fa-solid fa-spa text-danger me-2"></i>FloraCharm</h4>
                    <p class="text-secondary small">
                        Hệ thống điện hoa tươi cao cấp uy tín hàng đầu. Cam kết 100% hoa tươi nhập khẩu và Đà Lạt tươi mới mỗi ngày, thiết kế theo yêu cầu, giao hoa hỏa tốc trong 60 phút.
                    </p>
                    <div class="text-secondary small">
                        <p class="mb-1"><i class="fa-solid fa-location-dot me-2 text-danger"></i> Số 1 Đại Cồ Việt, Hai Bà Trưng, Hà Nội</p>
                        <p class="mb-1"><i class="fa-solid fa-envelope me-2 text-danger"></i> contact@floracharm.vn</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-uppercase fw-bold mb-3 text-danger">Chủ Đề Hoa</h6>
                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2"><a href="{{ route('flowers.index', ['category' => 'hoa-sinh-nhat']) }}" class="text-secondary text-decoration-none">Hoa Sinh Nhật</a></li>
                        <li class="mb-2"><a href="{{ route('flowers.index', ['category' => 'hoa-khai-truong']) }}" class="text-secondary text-decoration-none">Hoa Khai Trương</a></li>
                        <li class="mb-2"><a href="{{ route('flowers.index', ['category' => 'hoa-tinh-yeu']) }}" class="text-secondary text-decoration-none">Hoa Tình Yêu</a></li>
                        <li class="mb-2"><a href="{{ route('flowers.index', ['category' => 'lan-ho-diep']) }}" class="text-secondary text-decoration-none">Lan Hồ Điệp</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-uppercase fw-bold mb-3 text-danger">Chính Sách & Hỗ Trợ</h6>
                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2"><a href="{{ route('orders.track') }}" class="text-secondary text-decoration-none">Tra cứu tiến độ đơn hàng</a></li>
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Chính sách bảo hành hoa 3 ngày</a></li>
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Quy định đổi trả & hoàn tiền</a></li>
                        <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Hình thức thanh toán VietQR / COD</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-uppercase fw-bold mb-3 text-danger">Cam Kết Vàng</h6>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa-solid fa-shield-halved text-danger fs-3 me-3"></i>
                        <span class="small text-secondary">Chụp hình hoa trước khi giao để quý khách duyệt.</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-gift text-danger fs-3 me-3"></i>
                        <span class="small text-secondary">Tặng miễn phí banner thiệp chúc mừng thiết kế riêng.</span>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="text-center text-secondary small">
                © 2026 FloraCharm Flower Shop. Đồ án môn học Lập trình Web Laravel nhóm 5 thành viên.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS & SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AJAX Add to Cart Script -->
    <script>
        function addToCartAjax(productId, quantity = 1) {
            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Cập nhật số lượng giỏ hàng
                    document.getElementById('cartCountBadge').textContent = data.cart_count;

                    // Hiển thị Toast thông báo đẹp
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: data.message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Thông báo',
                        text: data.message
                    });
                }
            })
            .catch(err => {
                console.error(err);
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
