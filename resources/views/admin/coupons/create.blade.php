@extends('layouts.admin')

@section('title', 'Tạo Mã Giảm Giá Mới - FloraCharm Admin')
@section('page_title', 'Tạo Voucher Khuyến Mãi Mới')

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white p-4 mx-auto" style="max-width: 700px;">
    <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Mã code voucher <span class="text-danger">*</span></label>
                <input type="text" name="code" value="{{ old('code') }}" class="form-control rounded-pill text-uppercase @error('code') is-invalid @enderror" placeholder="Ví dụ: FLORA99, GIAM50K" required>
                @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Loại giảm giá <span class="text-danger">*</span></label>
                <select name="discount_type" class="form-select rounded-pill" required>
                    <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Giảm theo phần trăm (%)</option>
                    <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Giảm tiền cố định (VNĐ)</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Mức giảm giá <span class="text-danger">*</span></label>
                <input type="number" name="discount_value" value="{{ old('discount_value') }}" class="form-control rounded-pill @error('discount_value') is-invalid @enderror" placeholder="Ví dụ: 99 hoặc 50000" required>
                @error('discount_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Đơn hàng tối thiểu (VNĐ)</label>
                <input type="number" name="min_order_amount" value="{{ old('min_order_amount', 0) }}" class="form-control rounded-pill" placeholder="0">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Ngày hết hạn (Nếu có)</label>
                <input type="date" name="expires_at" value="{{ old('expires_at') }}" class="form-control rounded-pill">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Số lượt dùng tối đa</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit', 1000) }}" class="form-control rounded-pill" placeholder="Để trống nếu không giới hạn">
            </div>

            <div class="col-12">
                <div class="form-check p-3 bg-light rounded-4">
                    <input class="form-check-input ms-0 me-2" type="checkbox" name="is_active" id="activeCheck" value="1" checked>
                    <label class="form-check-label fw-semibold" for="activeCheck">
                        Kích hoạt mã ngay sau khi tạo
                    </label>
                </div>
            </div>

            <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Hủy bỏ</a>
                <button type="submit" class="btn btn-danger rounded-pill px-4">
                    <i class="fa-solid fa-plus me-1"></i> Tạo Mã Voucher
                </button>
            </div>
        </div>
    </form>
</div>
@endsection