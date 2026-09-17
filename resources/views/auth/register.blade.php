@extends('layouts.app')

@section('title', 'Đăng Ký Tài Khoản Mới - FloraCharm')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="bg-white p-5 rounded-5 shadow-sm border">
                <div class="text-center mb-4">
                    <i class="fa-solid fa-spa text-danger fs-1 mb-2"></i>
                    <h3 class="font-serif fw-bold">Tạo Tài Khoản</h3>
                    <p class="text-muted small">Đăng ký để nhận ưu đãi và quản lý đơn hàng dễ dàng</p>
                </div>

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control rounded-pill @error('name') is-invalid @enderror" placeholder="Ví dụ: Nguyễn Văn An" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Địa chỉ Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-pill @error('email') is-invalid @enderror" placeholder="name@example.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control rounded-pill @error('phone') is-invalid @enderror" placeholder="0912345678" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Địa chỉ mặc định</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="form-control rounded-pill @error('address') is-invalid @enderror" placeholder="Số nhà, tên đường, quận/huyện...">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control rounded-pill @error('password') is-invalid @enderror" placeholder="Ít nhất 6 ký tự" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control rounded-pill" placeholder="Nhập lại mật khẩu" required>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-flower py-2">Đăng Ký Tài Khoản</button>
                    </div>

                    <div class="text-center small text-muted">
                        Đã có tài khoản? <a href="{{ route('login') }}" class="text-danger fw-semibold text-decoration-none">Đăng nhập</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
