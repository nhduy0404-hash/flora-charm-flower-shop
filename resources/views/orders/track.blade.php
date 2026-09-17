@extends('layouts.app')

@section('title', 'Tra Cứu Tiến Độ Đơn Hàng - FloraCharm')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-6 font-serif fw-bold">Tra Cứu Tiến Độ Đơn Hàng</h1>
        <p class="text-muted">Nhập Mã đơn hàng hoặc Số điện thoại để theo dõi quá trình cắm và giao hoa.</p>

        <!-- Form Tra Cứu -->
        <div class="mx-auto bg-white p-4 rounded-4 shadow-sm border mt-4" style="max-width: 650px;">
            <form action="{{ route('orders.track') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-6 text-start">
                        <label class="form-label small fw-semibold">Mã đơn hàng</label>
                        <input type="text" name="order_number" value="{{ request('order_number') }}" class="form-control rounded-pill text-uppercase" placeholder="Ví dụ: FLW-2026...">
                    </div>
                    <div class="col-md-6 text-start">
                        <label class="form-label small fw-semibold">Số điện thoại nhận hoa</label>
                        <input type="tel" name="phone" value="{{ request('phone') }}" class="form-control rounded-pill" placeholder="Ví dụ: 0369710409">
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-flower w-100 py-2">
                            <i class="fa-solid fa-magnifying-glass me-2"></i> Tra Cứu Tiến Độ Đơn Hàng
                        </button>
                    </div>
                </div>
            </form>
            @auth
                <div class="text-center mt-3 pt-2 border-top">
                    <a href="{{ route('orders.history') }}" class="text-danger small text-decoration-none fw-semibold">
                        <i class="fa-solid fa-list-check me-1"></i> Xem toàn bộ lịch sử đơn hàng của bạn
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <!-- 1. Kết Quả: Danh Sách Nhiều Đơn Hàng (Tìm Theo Số Điện Thoại) -->
    @if(isset($multipleOrders) && $multipleOrders->isNotEmpty())
        <div class="mx-auto mb-5" style="max-width: 850px;">
            <h5 class="fw-bold mb-3 font-serif"><i class="fa-solid fa-receipt me-2 text-danger"></i> Tìm Thấy {{ $multipleOrders->count() }} Đơn Hàng Cho Số: {{ request('phone') }}</h5>
            <div class="space-y-3">
                @foreach($multipleOrders as $mOrder)
                    <div class="bg-white p-3 rounded-4 shadow-sm border mb-3 d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <strong class="text-danger fs-6">{{ $mOrder->order_number }}</strong>
                            <span class="text-muted small ms-2">Ngày đặt: {{ $mOrder->created_at->format('d/m/Y') }}</span>
                            <div class="small text-muted mt-1">Người nhận: <strong>{{ $mOrder->receiver_name }}</strong> | Hẹn giao: {{ $mOrder->delivery_date->format('d/m/Y') }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-dark mb-1">{{ number_format($mOrder->total_amount) }} đ</div>
                            <a href="{{ route('orders.track', ['order_number' => $mOrder->order_number]) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                Chi Tiết Tiến Độ <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 2. Kết Quả: Chi Tiết Tiến Độ Đơn Hàng Cụ Thể -->
    @if(isset($order) && $order)
        <div class="bg-white p-4 p-md-5 rounded-5 shadow-sm border mx-auto" style="max-width: 850px;">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <span class="text-muted small">Mã đơn hàng:</span>
                    <h5 class="fw-bold text-danger mb-0">{{ $order->order_number }}</h5>
                    <small class="text-muted">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</small>
                </div>
                <div class="text-end mt-2 mt-sm-0">
                    <span class="text-muted small d-block">Trạng thái:</span>
                    @switch($order->order_status)
                        @case('pending')
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fa-solid fa-hourglass-start me-1"></i> 1. Chờ Xác Nhận</span>
                            @break
                        @case('processing')
                            <span class="badge bg-info text-dark px-3 py-2 rounded-pill"><i class="fa-solid fa-scissors me-1"></i> 2. Đang Cắm Hoa</span>
                            @break
                        @case('delivering')
                            <span class="badge bg-primary px-3 py-2 rounded-pill"><i class="fa-solid fa-truck-fast me-1"></i> 3. Đang Giao Hàng</span>
                            @break
                        @case('completed')
                            <span class="badge bg-success px-3 py-2 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i> 4. Giao Thành Công</span>
                            @break
                        @case('cancelled')
                            <span class="badge bg-danger px-3 py-2 rounded-pill"><i class="fa-solid fa-ban me-1"></i> Đã Hủy</span>
                            @break
                    @endswitch
                </div>
            </div>

            <!-- Visual Progress Timeline 4 Bước -->
            @php
                $statusMap = ['pending' => 0, 'processing' => 1, 'delivering' => 2, 'completed' => 3];
                $stepIndex = $statusMap[$order->order_status] ?? 0;
            @endphp

            @if($order->order_status !== 'cancelled')
                <div class="position-relative my-4 px-3">
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($stepIndex / 3) * 100 }}%;"></div>
                    </div>
                    <div class="d-flex justify-content-between position-absolute top-50 start-0 w-100 translate-middle-y px-3">
                        <button type="button" class="btn btn-sm btn-{{ $stepIndex >= 0 ? 'danger' : 'secondary' }} rounded-circle shadow-sm" style="width: 2.4rem; height: 2.4rem;">
                            <i class="fa-solid fa-file-invoice"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-{{ $stepIndex >= 1 ? 'danger' : 'secondary' }} rounded-circle shadow-sm" style="width: 2.4rem; height: 2.4rem;">
                            <i class="fa-solid fa-scissors"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-{{ $stepIndex >= 2 ? 'danger' : 'secondary' }} rounded-circle shadow-sm" style="width: 2.4rem; height: 2.4rem;">
                            <i class="fa-solid fa-truck-fast"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-{{ $stepIndex >= 3 ? 'danger' : 'secondary' }} rounded-circle shadow-sm" style="width: 2.4rem; height: 2.4rem;">
                            <i class="fa-solid fa-gift"></i>
                        </button>
                    </div>
                </div>
                <div class="d-flex justify-content-between small text-center text-muted mt-4 mb-5">
                    <span class="w-25 {{ $stepIndex >= 0 ? 'fw-bold text-danger' : '' }}">1. Tiếp Nhận Đơn</span>
                    <span class="w-25 {{ $stepIndex >= 1 ? 'fw-bold text-danger' : '' }}">2. Đang Cắm Hoa</span>
                    <span class="w-25 {{ $stepIndex >= 2 ? 'fw-bold text-danger' : '' }}">3. Đang Giao Tận Nơi</span>
                    <span class="w-25 {{ $stepIndex >= 3 ? 'fw-bold text-danger' : '' }}">4. Giao Thành Công</span>
                </div>
            @else
                <div class="alert alert-danger rounded-4 text-center my-4">
                    <i class="fa-solid fa-ban me-2"></i> Đơn hàng này đã bị hủy. Vui lòng liên hệ Hotline nếu bạn cần trợ giúp.
                </div>
            @endif

            <!-- Danh Sách Sản Phẩm Đã Đặt -->
            <div class="mb-4">
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-basket-shopping me-2 text-danger"></i> Mẫu Hoa Trong Đơn Hàng</h6>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle small mb-0">
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr class="border-bottom">
                                    <td style="width: 60px;">
                                        @if($item->product && $item->product->primary_image)
                                            <img src="{{ $item->product->primary_image }}" alt="{{ $item->product_name }}" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fa-solid fa-spa text-danger"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                        <small class="text-muted">Đơn giá: {{ number_format($item->unit_price) }} đ</small>
                                    </td>
                                    <td class="text-center">x {{ $item->quantity }}</td>
                                    <td class="text-end fw-bold">{{ number_format($item->total_price) }} đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Thông Tin Người Nhận & Thanh Toán -->
            <div class="row g-4 text-start small mb-4">
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded-4 h-100">
                        <h6 class="fw-bold text-danger mb-2"><i class="fa-solid fa-location-dot me-1"></i> Thông Tin Giao Nhận</h6>
                        <p class="mb-1 text-muted">Người nhận: <strong class="text-dark">{{ $order->receiver_name }}</strong></p>
                        <p class="mb-1 text-muted">Số điện thoại: <strong class="text-dark">{{ $order->receiver_phone }}</strong></p>
                        <p class="mb-1 text-muted">Hẹn giao: <strong class="text-dark">{{ $order->delivery_date->format('d/m/Y') }}</strong> ({{ $order->delivery_time_slot }})</p>
                        <p class="mb-0 text-muted">Địa chỉ: <strong class="text-dark">{{ $order->shipping_address }}</strong></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-light p-3 rounded-4 h-100">
                        <h6 class="fw-bold text-danger mb-2"><i class="fa-solid fa-credit-card me-1"></i> Thanh Toán & Thiệp Mừng</h6>
                        <p class="mb-1 text-muted">Hình thức: <strong>{{ $order->payment_method === 'bank_transfer' ? 'Chuyển khoản VietQR' : 'Thanh toán khi nhận (COD)' }}</strong></p>
                        <p class="mb-1 text-muted">Trạng thái: 
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success-subtle text-success">Đã thanh toán</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Chưa thanh toán</span>
                            @endif
                        </p>
                        @if($order->discount_amount > 0)
                            <p class="mb-1 text-success">Giảm giá voucher: <strong>-{{ number_format($order->discount_amount) }} đ</strong></p>
                        @endif
                        <p class="mb-2 text-muted">Tổng thanh toán: <strong class="text-danger fs-6">{{ number_format($order->total_amount) }} đ</strong></p>
                        
                        @if($order->card_message)
                            <div class="p-2 bg-white rounded-3 fst-italic text-dark border mt-2">
                                <span class="text-muted small d-block">Lời chúc thiệp:</span> "{{ $order->card_message }}"
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Nút hành động thanh toán VietQR nếu chưa trả -->
            @if($order->payment_method === 'bank_transfer' && $order->payment_status === 'unpaid')
                <div class="text-center pt-2">
                    <a href="{{ route('checkout.success', $order->order_number) }}" class="btn btn-flower px-4 py-2">
                        <i class="fa-solid fa-qrcode me-2"></i> Mở Mã QR Chuyển Khoản Ngân Hàng
                    </a>
                </div>
            @endif
        </div>
    @endif

    <!-- 3. Gợi ý Đơn Hàng Gần Đây Của Khách (Khi Đã Đăng Nhập) -->
    @if(isset($userRecentOrders) && $userRecentOrders->isNotEmpty())
        <div class="mx-auto mt-5" style="max-width: 850px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold font-serif mb-0"><i class="fa-solid fa-clock-rotate-left me-2 text-danger"></i> Đơn Đặt Hoa Gần Đây Của Bạn</h5>
                <a href="{{ route('orders.history') }}" class="small text-danger text-decoration-none fw-semibold">Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
            <div class="space-y-3">
                @foreach($userRecentOrders as $uOrder)
                    <div class="bg-white p-3 rounded-4 shadow-sm border mb-3 d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <strong class="text-danger fs-6">{{ $uOrder->order_number }}</strong>
                            <span class="text-muted small ms-2">Đặt lúc: {{ $uOrder->created_at->format('d/m/Y H:i') }}</span>
                            <div class="small text-muted mt-1">Người nhận: <strong>{{ $uOrder->receiver_name }}</strong> | Hẹn giao: {{ $uOrder->delivery_date->format('d/m/Y') }} ({{ $uOrder->delivery_time_slot }})</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-danger mb-1">{{ number_format($uOrder->total_amount) }} đ</div>
                            <a href="{{ route('orders.track', ['order_number' => $uOrder->order_number]) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                Xem Tiến Độ <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection