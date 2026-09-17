@extends('layouts.app')

@section('title', 'Đăng Nhập Tài Khoản - FloraCharm')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="bg-white p-5 rounded-5 shadow-sm border">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-spa text-danger fs-1 mb-2"></i>
                    <h3 class="font-serif fw-bold">Đăng Nhập</h3>
                    <p class="text-muted small">Chào mừng bạn quay trở lại với FloraCharm</p>
                </div>

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Địa chỉ Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-pill @error('email') is-invalid @enderror" placeholder="name@example.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control rounded-pill @error('password') is-invalid @enderror" placeholder="••••••••" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 small">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                            <label class="form-check-label text-muted" for="rememberMe">Ghi nhớ đăng nhập</label>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-flower py-2">Đăng Nhập</button>
                    </div>

                    <div class="text-center small text-muted">
                        Chưa có tài khoản? <a href="{{ route('register') }}" class="text-danger fw-semibold text-decoration-none">Đăng ký ngay</a>
                    </div>
                </form>

                <!-- Gợi ý tài khoản demo -->
                <div class="mt-4 pt-3 border-top small text-muted text-center">
                    <p class="mb-1"><strong>Tài khoản Demo:</strong></p>
                    <p class="mb-1">Admin: <code>admin@flowershop.vn</code> / <code>admin123</code></p>
                    <p class="mb-0">Khách: <code>khachhang@gmail.com</code> / <code>password123</code></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
