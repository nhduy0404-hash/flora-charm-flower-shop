@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng - FloraCharm Admin')
@section('page_title', 'Danh Sách Đơn Hàng Hoa')

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white p-4">
    <!-- Thanh Tìm Kiếm & Bộ Lọc Đơn Hàng -->
    <div class="row g-3 align-items-center mb-4 pb-3 border-bottom">
        <div class="col-lg-5">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex gap-2">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="input-group">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control rounded-start-pill" placeholder="Tìm theo Mã đơn, Tên người nhận, SĐT...">
                    <button type="submit" class="btn btn-danger rounded-end-pill px-3">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
                @if(request('keyword'))
                    <a href="{{ route('admin.orders.index', ['status' => request('status')]) }}" class="btn btn-outline-secondary rounded-pill" title="Xóa tìm kiếm">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="col-lg-7 text-lg-end">
            <div class="d-inline-flex flex-wrap gap-1">
                <a href="{{ route('admin.orders.index', ['keyword' => request('keyword')]) }}" class="btn btn-sm {{ !request('status') ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill px-3">Tất cả</a>
                <a href="{{ route('admin.orders.index', ['status' => 'pending', 'keyword' => request('keyword')]) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill px-3">Chờ duyệt</a>
                <a href="{{ route('admin.orders.index', ['status' => 'processing', 'keyword' => request('keyword')]) }}" class="btn btn-sm {{ request('status') == 'processing' ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill px-3">Đang cắm</a>
                <a href="{{ route('admin.orders.index', ['status' => 'delivering', 'keyword' => request('keyword')]) }}" class="btn btn-sm {{ request('status') == 'delivering' ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill px-3">Đang giao</a>
                <a href="{{ route('admin.orders.index', ['status' => 'completed', 'keyword' => request('keyword')]) }}" class="btn btn-sm {{ request('status') == 'completed' ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill px-3">Đã giao</a>
                <a href="{{ route('admin.orders.index', ['status' => 'cancelled', 'keyword' => request('keyword')]) }}" class="btn btn-sm {{ request('status') == 'cancelled' ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill px-3">Đã hủy</a>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th>Mã Đơn</th>
                    <th>Người Nhận & SĐT</th>
                    <th>Ngày & Giờ Giao</th>
                    <th>Tổng Tiền</th>
                    <th>Hình Thức</th>
                    <th>Thanh Toán</th>
                    <th>Tiến Độ</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="fw-bold text-danger">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-danger text-decoration-none">
                                {{ $order->order_number }}
                            </a>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $order->created_at->format('d/m H:i') }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $order->receiver_name }}</div>
                            <small class="text-muted"><i class="fa-solid fa-phone me-1"></i>{{ $order->receiver_phone }}</small>
                        </td>
                        <td>
                            <div>{{ $order->delivery_date->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $order->delivery_time_slot }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ number_format($order->total_amount) }} đ</div>
                            @if($order->discount_amount > 0)
                                <small class="text-success">(Giảm {{ number_format($order->discount_amount) }} đ)</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ strtoupper($order->payment_method) }}</span>
                        </td>
                        <td>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                    <i class="fa-solid fa-check me-1"></i> Đã Thu
                                </span>
                            @else
                                <div class="d-flex align-items-center gap-1">
                                    <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-1">
                                        Chưa Thu
                                    </span>
                                    <form action="{{ route('admin.orders.quickMarkPaid', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận bạn đã nhận được tiền cho đơn #{{ $order->order_number }}?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-xs btn-outline-success rounded-pill py-0 px-2" style="font-size: 0.75rem;" title="1-click xác nhận đã nhận tiền">
                                            <i class="fa-solid fa-check"></i> Đã thu
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                        <td>
                            @switch($order->order_status)
                                @case('pending')
                                    <span class="badge bg-warning text-dark rounded-pill">Chờ duyệt</span>
                                    @break
                                @case('processing')
                                    <span class="badge bg-info text-dark rounded-pill">Đang cắm hoa</span>
                                    @break
                                @case('delivering')
                                    <span class="badge bg-primary rounded-pill">Đang giao</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success rounded-pill">Đã giao</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger rounded-pill">Đã hủy</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                Chi tiết
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fa-solid fa-inbox fs-2 text-secondary d-block mb-2"></i>
                            Không tìm thấy đơn hàng nào phù hợp với điều kiện lọc.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
</div>
@endsection