@extends('layouts.admin')

@section('title', 'Bảng Điều Khiển - FloraCharm Admin')
@section('page_title', 'Tổng Quan Doanh Thu & Đơn Hàng')

@section('content')
    <!-- Thống kê thẻ số liệu (Metrics Cards) -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-4 bg-danger-subtle text-danger p-3 me-3">
                        <i class="fa-solid fa-coins fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Doanh Thu Thực Thu</span>
                        <h4 class="fw-bold mb-0 text-danger">{{ number_format($totalRevenue) }} đ</h4>
                        <small class="text-muted" style="font-size: 0.75rem;">(Chờ thu: {{ number_format($unpaidRevenue) }} đ)</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-4 bg-primary-subtle text-primary p-3 me-3">
                        <i class="fa-solid fa-receipt fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Tổng Số Đơn Hàng</span>
                        <h4 class="fw-bold mb-0 text-primary">{{ $totalOrders }} đơn</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-4 bg-warning-subtle text-warning p-3 me-3">
                        <i class="fa-solid fa-hourglass-half fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Đơn Chờ Xử Lý</span>
                        <h4 class="fw-bold mb-0 text-warning">{{ $pendingOrders }} đơn</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-4 bg-success-subtle text-success p-3 me-3">
                        <i class="fa-solid fa-seedling fs-3"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Mẫu Hoa Đang Bán</span>
                        <h4 class="fw-bold mb-0 text-success">{{ $totalProducts }} mẫu</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng 10 Đơn Hàng Mới Nhất -->
    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Đơn Hàng Mới Nhất</h5>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-danger rounded-pill">Xem tất cả đơn</a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Người Nhận</th>
                        <th>Ngày & Giờ Giao</th>
                        <th>Tổng Tiền</th>
                        <th>Thanh Toán</th>
                        <th>Trạng Thái</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $ord)
                        <tr>
                            <td class="fw-bold text-danger">{{ $ord->order_number }}</td>
                            <td>
                                <div>{{ $ord->receiver_name }}</div>
                                <small class="text-muted">{{ $ord->receiver_phone }}</small>
                            </td>
                            <td>
                                <div>{{ $ord->delivery_date->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $ord->delivery_time_slot }}</small>
                            </td>
                            <td class="fw-bold">{{ number_format($ord->total_amount) }} đ</td>
                            <td>
                                @if($ord->payment_status === 'paid')
                                    <span class="badge bg-success rounded-pill">Đã Thanh Toán</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">Chưa Thu</span>
                                @endif
                            </td>
                            <td>
                                @switch($ord->order_status)
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
                                        <span class="badge bg-success rounded-pill">Hoàn thành</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger rounded-pill">Đã hủy</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                    Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Chưa có đơn hàng nào trong hệ thống.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
