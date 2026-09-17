@extends('layouts.admin')

@section('title', 'Quản Lý Mã Khuyến Mãi - FloraCharm Admin')
@section('page_title', 'Quản Lý Mã Giảm Giá & Voucher')

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0 font-serif">Danh Sách Voucher Khuyến Mãi</h5>
            <small class="text-muted">Quản lý các mã giảm giá áp dụng khi khách hàng thanh toán.</small>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-danger rounded-pill px-4">
            <i class="fa-solid fa-plus me-1"></i> Tạo Mã Voucher Mới
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th>Mã Voucher</th>
                    <th>Mức Giảm</th>
                    <th>Đơn Tối Thiểu</th>
                    <th>Hạn Dùng</th>
                    <th>Lượt Dùng</th>
                    <th>Trạng Thái</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $c)
                    <tr>
                        <td>
                            <strong class="text-danger fs-6">{{ $c->code }}</strong>
                        </td>
                        <td>
                            @if($c->discount_type === 'percentage')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-6">
                                    -{{ (int)$c->discount_value }}%
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-6">
                                    -{{ number_format($c->discount_value) }} đ
                                </span>
                            @endif
                        </td>
                        <td>{{ number_format($c->min_order_amount) }} đ</td>
                        <td>
                            @if($c->expires_at)
                                <small class="text-muted">{{ $c->expires_at->format('d/m/Y') }}</small>
                            @else
                                <small class="text-success fw-semibold">Vô thời hạn</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $c->used_count }} / {{ $c->usage_limit ?: '∞' }}
                            </span>
                            @if($c->orders_count > 0)
                                <small class="text-muted d-block">({{ $c->orders_count }} đơn hàng)</small>
                            @endif
                        </td>
                        <td>
                            @if($c->is_active)
                                <span class="badge bg-success rounded-pill">Đang Hoạt Động</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">Tạm Khóa</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <a href="{{ route('admin.coupons.edit', $c->id) }}" class="btn btn-sm btn-outline-primary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;" title="Sửa mã">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hoặc vô hiệu hóa voucher này?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;" title="Xóa/Khóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Chưa có mã khuyến mãi nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $coupons->links() }}
    </div>
</div>
@endsection