@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng - FloraCharm Admin')
@section('page_title', 'Danh Sách Đơn Hàng Hoa')

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white p-4">
    <!-- Bộ Lọc Đơn Hàng -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <h5 class="fw-bold mb-0">Tất Cả Đơn Hàng</h5>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill">Tất cả</a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill">Chờ duyệt</a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="btn btn-sm {{ request('status') == 'processing' ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill">Đang cắm hoa</a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivering']) }}" class="btn btn-sm {{ request('status') == 'delivering' ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill">Đang giao</a>
            <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="btn btn-sm {{ request('status') == 'completed' ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill">Đã giao</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th>Mã Đơn</th>
                    <th>Người Nhận & SĐT</th>
                    <th>Ngày & Khung Giờ Giao</th>
                    <th>Tổng Tiền</th>
                    <th>Phương Thức</th>
                    <th>Thanh Toán</th>
                    <th>Trạng Thái Đơn</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="fw-bold text-danger">{{ $order->order_number }}</td>
                        <td>
                            <div class="fw-semibold">{{ $order->receiver_name }}</div>
                            <small class="text-muted">{{ $order->receiver_phone }}</small>
                        </td>
                        <td>
                            <div>{{ $order->delivery_date->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $order->delivery_time_slot }}</small>
                        </td>
                        <td class="fw-bold">{{ number_format($order->total_amount) }} đ</td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ strtoupper($order->payment_method) }}</span>
                        </td>
                        <td>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success rounded-pill">Đã Thanh Toán</span>
                            @else
                                <span class="badge bg-secondary rounded-pill">Chưa Thu</span>
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
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-danger rounded-pill">
                                Xem & Duyệt
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Chưa có đơn hàng nào theo điều kiện lọc này.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="mt-3 d-flex justify-content-center">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
