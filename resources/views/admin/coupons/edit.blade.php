@extends('layouts.admin')

@section('title', 'Sửa Mã Giảm Giá - FloraCharm Admin')
@section('page_title', 'Chỉnh Sửa Voucher: ' . $coupon->code)

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white p-4 mx-auto" style="max-width: 700px;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h5 class="fw-bold mb-0 font-serif">Chỉnh Sửa Voucher: {{ $coupon->code }}</h5>
            <small class="text-muted">Đã sử dụng: {{ $coupon->used_count }} lần</small>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Mã code voucher <span class="text-danger">*</span></label>
                <input type="text" name="code" value="{{ old('code', $coupon->code) }}" class="form-control rounded-pill text-uppercase @error('code') is-invalid @enderror" required>
                @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Loại giảm giá <span class="text-danger">*</span></label>
                <select name="discount_type" class="form-select rounded-pill" required>
                    <option value="percentage" {{ old('discount_type', $coupon->discount_type) === 'percentage' ? 'selected' : '' }}>Giảm theo phần trăm (%)</option>
                    <option value="fixed" {{ old('discount_type', $coupon->discount_type) === 'fixed' ? 'selected' : '' }}>Giảm tiền cố định (VNĐ)</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Mức giảm giá <span class="text-danger">*</span></label>
                <input type="number" name="discount_value" value="{{ old('discount_value', (int)$coupon->discount_value) }}" class="form-control rounded-pill @error('discount_value') is-invalid @enderror" required>
                @error('discount_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Đơn hàng tối thiểu (VNĐ)</label>
                <input type="number" name="min_order_amount" value="{{ old('min_order_amount', (int)$coupon->min_order_amount) }}" class="form-control rounded-pill">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Ngày hết hạn</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}" class="form-control rounded-pill">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Số lượt dùng tối đa</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" class="form-control rounded-pill">
            </div>

            <div class="col-12">
                <div class="form-check p-3 bg-light rounded-4">
                    <input class="form-check-input ms-0 me-2" type="checkbox" name="is_active" id="activeCheck" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="activeCheck">
                        Đang kích hoạt voucher
                    </label>
                </div>
            </div>

            <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Hủy bỏ</a>
                <button type="submit" class="btn btn-danger rounded-pill px-4">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cập Nhật
                </button>
            </div>
        </div>
    </form>
</div>
@endsection