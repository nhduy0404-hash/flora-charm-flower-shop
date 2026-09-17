@extends('layouts.app')

@section('title', 'Đặt Hàng Thành Công - FloraCharm')

@section('content')
<div class="container py-5 text-center">
    <div class="bg-white p-5 rounded-5 shadow-sm border mx-auto" style="max-width: 760px;">
        <div class="rounded-circle bg-success-subtle text-success d-inline-flex p-3 mb-3">
            <i class="fa-solid fa-circle-check fs-1"></i>
        </div>
        <h2 class="display-6 font-serif fw-bold mb-2">Cảm Ơn Bạn Đã Đặt Hoa Tại FloraCharm!</h2>
        <p class="text-muted mb-4">Mã đơn hàng của bạn là: <strong class="text-danger fs-5">{{ $order->order_number }}</strong></p>

        <!-- Nếu thanh toán Chuyển khoản VietQR -->
        @if($vietQrUrl)
            <div class="alert alert-light border border-danger-subtle rounded-4 p-4 text-start mb-4">
                <div class="row align-items-center">
                    <div class="col-md-5 text-center mb-3 mb-md-0">
                        <img src="{{ $vietQrUrl }}" alt="Mã QR Chuyển Khoản VietQR" class="img-fluid rounded-3 shadow-sm border p-2 bg-white" style="max-width: 220px;">
                        <small class="d-block text-muted mt-2"><i class="fa-solid fa-qrcode me-1"></i> Quét bằng App Ngân Hàng bất kỳ</small>
                    </div>
                    <div class="col-md-7">
                        <h6 class="fw-bold text-danger mb-3"><i class="fa-solid fa-building-columns me-2"></i> Thông Tin Chuyển Khoản Ngân Hàng</h6>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2">Ngân hàng: <strong>MB Bank (Ngân hàng Quân Đội)</strong></li>
                            <li class="mb-2">Số tài khoản: <strong class="text-danger fs-5">0369710409</strong></li>
                            <li class="mb-2">Tên chủ tài khoản: <strong>{{ $bankAccountName ?? 'FLORA CHARM' }}</strong></li>
                            <li class="mb-2">Số tiền: <strong class="text-danger fs-5">{{ number_format($order->total_amount) }} đ</strong></li>
                            <li class="mb-0">Nội dung chuyển khoản: <strong class="badge bg-secondary p-2 fs-6">{{ $order->order_number }}</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info rounded-4 text-start p-3 mb-4 small">
                <i class="fa-solid fa-info-circle me-1"></i> Bạn đã chọn hình thức <strong>Thanh toán tiền mặt khi nhận hoa (COD)</strong>. Shipper sẽ liên hệ trước khi giao hoa.
            </div>
        @endif

        <!-- Tóm tắt người nhận và thiệp -->
        <div class="bg-light p-4 rounded-4 text-start small mb-4">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-clipboard-list me-2 text-danger"></i> Chi Tiết Đơn Hàng</h6>
            <div class="row g-2">
                <div class="col-sm-6">Người nhận: <strong>{{ $order->receiver_name }}</strong> ({{ $order->receiver_phone }})</div>
                <div class="col-sm-6">Thời gian giao: <strong>{{ $order->delivery_date->format('d/m/Y') }}</strong> ({{ $order->delivery_time_slot }})</div>
                <div class="col-12">Địa chỉ giao: <strong>{{ $order->shipping_address }}</strong></div>
                @if($order->discount_amount > 0)
                    <div class="col-sm-6 text-muted">Tạm tính tiền hoa: {{ number_format($order->subtotal) }} đ</div>
                    <div class="col-sm-6 text-success fw-bold">Giảm giá voucher: -{{ number_format($order->discount_amount) }} đ</div>
                @endif
                <div class="col-12 fw-bold text-danger fs-6 pt-1">Tổng cộng thanh toán: {{ number_format($order->total_amount) }} đ</div>
                @if($order->card_message)
                    <div class="col-12 mt-2 pt-2 border-top">
                        <span class="text-muted">Lời chúc in thiệp:</span>
                        <div class="fst-italic text-dark mt-1 p-2 bg-white rounded border">"{{ $order->card_message }}"</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('orders.track', ['order_number' => $order->order_number, 'phone' => $order->receiver_phone]) }}" class="btn btn-outline-danger rounded-pill px-4">
                <i class="fa-solid fa-magnifying-glass me-1"></i> Tra Cứu Tiến Độ Đơn Hàng
            </a>
            <a href="{{ route('home') }}" class="btn btn-flower px-4">
                Quay Lại Trang Chủ
            </a>
        </div>
    </div>
</div>
@endsection
