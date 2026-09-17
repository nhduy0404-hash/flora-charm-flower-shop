@extends('layouts.app')

@section('title', 'Lịch Sử Mua Hàng - FloraCharm')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 font-serif fw-bold mb-1">Lịch Sử Mua Hàng Của Tôi</h1>
            <p class="text-muted mb-0">Theo dõi chi tiết các đơn đặt hoa và tiến độ giao hoa của bạn.</p>
        </div>
        <div>
            <a href="{{ route('flowers.index') }}" class="btn btn-flower">
                <i class="fa-solid fa-plus me-1"></i> Đặt Thêm Hoa Tươi
            </a>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white p-5 rounded-5 shadow-sm border text-center my-4">
            <div class="rounded-circle bg-light text-muted d-inline-flex p-4 mb-3">
                <i class="fa-solid fa-receipt fs-1 text-danger"></i>
            </div>
            <h4 class="fw-bold font-serif mb-2">Bạn Chưa Có Đơn Đặt Hoa Nào</h4>
            <p class="text-muted mb-4">Hãy chọn những mẫu hoa tươi thắm nhất dành tặng người thân yêu ngay hôm nay!</p>
            <a href="{{ route('flowers.index') }}" class="btn btn-flower px-4 py-2">
                <i class="fa-solid fa-basket-shopping me-2"></i> Khám Phá Bộ Sưu Tập Hoa
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                    <!-- Header Đơn Hàng -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center pb-3 border-bottom mb-3">
                        <div class="mb-2 mb-md-0">
                            <span class="text-muted small">Mã đơn hàng:</span>
                            <strong class="text-danger fs-6 ms-1">{{ $order->order_number }}</strong>
                            <span class="text-muted small ms-3">
                                <i class="fa-regular fa-clock me-1"></i> Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <!-- Trạng thái thanh toán -->
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small">
                                    <i class="fa-solid fa-check me-1"></i> Đã Thanh Toán
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 small">
                                    <i class="fa-solid fa-clock me-1"></i> Chưa Thanh Toán
                                </span>
                            @endif

                            <!-- Tiến độ đơn hàng -->
                            @switch($order->order_status)
                                @case('pending')
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                        <i class="fa-solid fa-hourglass-start me-1"></i> 1. Chờ Xác Nhận
                                    </span>
                                    @break
                                @case('processing')
                                    <span class="badge bg-info text-dark px-3 py-2 rounded-pill">
                                        <i class="fa-solid fa-scissors me-1"></i> 2. Đang Cắm Hoa
                                    </span>
                                    @break
                                @case('delivering')
                                    <span class="badge bg-primary px-3 py-2 rounded-pill">
                                        <i class="fa-solid fa-truck-fast me-1"></i> 3. Đang Giao Hàng
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success px-3 py-2 rounded-pill">
                                        <i class="fa-solid fa-circle-check me-1"></i> 4. Giao Thành Công
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">
                                        <i class="fa-solid fa-ban me-1"></i> Đã Hủy
                                    </span>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <!-- Nội Dung Đơn Hàng -->
                    <div class="row g-4 align-items-center">
                        <!-- Danh sách sản phẩm -->
                        <div class="col-lg-7 border-end-lg">
                            <div class="space-y-3">
                                @foreach($order->orderItems as $item)
                                    <div class="d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        @if($item->product && $item->product->primary_image)
                                            <img src="{{ $item->product->primary_image }}" alt="{{ $item->product_name }}" class="rounded-3 me-3" style="width: 55px; height: 55px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 55px; height: 55px;">
                                                <i class="fa-solid fa-spa text-danger"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                            <small class="text-muted">{{ $item->quantity }} x {{ number_format($item->unit_price) }} đ</small>
                                        </div>
                                        <div class="fw-bold text-end small">
                                            {{ number_format($item->total_price) }} đ
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($order->card_message)
                                <div class="mt-3 p-2 bg-light rounded-3 small text-muted">
                                    <i class="fa-solid fa-envelope-open-text text-danger me-1"></i> Lời chúc thiệp:
                                    <span class="fst-italic text-dark">"{{ $order->card_message }}"</span>
                                </div>
                            @endif
                        </div>

                        <!-- Thông tin người nhận & Thanh toán -->
                        <div class="col-lg-5">
                            <div class="small mb-3">
                                <div class="mb-1 text-muted">Người nhận: <strong class="text-dark">{{ $order->receiver_name }}</strong> ({{ $order->receiver_phone }})</div>
                                <div class="mb-1 text-muted">Hẹn giao: <strong class="text-dark">{{ $order->delivery_date->format('d/m/Y') }}</strong> ({{ $order->delivery_time_slot }})</div>
                                <div class="mb-1 text-muted">Địa chỉ: <span class="text-dark">{{ $order->shipping_address }}</span></div>
                                <div class="text-muted">Hình thức: <strong>{{ $order->payment_method === 'bank_transfer' ? 'Chuyển khoản VietQR' : 'Thanh toán khi nhận (COD)' }}</strong></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-baseline pt-2 border-top mb-3">
                                <div>
                                    <span class="fw-bold text-dark">Tổng cộng:</span>
                                    @if($order->discount_amount > 0)
                                        <small class="text-success d-block">(Đã giảm {{ number_format($order->discount_amount) }} đ)</small>
                                    @endif
                                </div>
                                <div class="fs-5 fw-bold text-danger">
                                    {{ number_format($order->total_amount) }} đ
                                </div>
                            </div>

                            <!-- Nút hành động -->
                            <div class="d-flex flex-wrap gap-2 justify-content-end">
                                <a href="{{ route('orders.track', ['order_number' => $order->order_number, 'phone' => $order->receiver_phone]) }}" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                    <i class="fa-solid fa-location-dot me-1"></i> Xem Tiến Độ Chi Tiết
                                </a>
                                @if($order->payment_method === 'bank_transfer' && $order->payment_status === 'unpaid')
                                    <a href="{{ route('checkout.success', $order->order_number) }}" class="btn btn-sm btn-flower rounded-pill px-3">
                                        <i class="fa-solid fa-qrcode me-1"></i> Quét Mã VietQR
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Phân trang -->
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    @endif
</div>
@endsection