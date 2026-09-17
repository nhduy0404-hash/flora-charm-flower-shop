@extends('layouts.app')

@section('title', '403 - Truy Cập Bị Từ Chối | FloraCharm')

@section('content')
<div class="container py-5 text-center">
    <div class="bg-white p-5 rounded-5 shadow-sm border mx-auto my-4" style="max-width: 600px;">
        <div class="rounded-circle bg-danger-subtle text-danger d-inline-flex p-4 mb-3">
            <i class="fa-solid fa-user-shield fs-1"></i>
        </div>
        <h1 class="display-4 fw-bold text-danger font-serif mb-2">403</h1>
        <h4 class="fw-bold font-serif mb-3">Khu Vực Hạn Chế Quyền Truy Cập</h4>
        <p class="text-muted mb-4">
            {{ $exception->getMessage() ?: 'Bạn đang sử dụng tài khoản Khách hàng thông thường và không có quyền truy cập vào Khu vực Quản trị viên (Admin Portal).' }}
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('home') }}" class="btn btn-flower px-4 py-2">
                <i class="fa-solid fa-house me-1"></i> Về Trang Chủ
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                    <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Đổi Tài Khoản
                </button>
            </form>
        </div>
    </div>
</div>
@endsection