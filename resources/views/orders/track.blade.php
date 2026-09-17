@extends('layouts.app')

@section('title', 'Tra Cứu Tiến Độ Đơn Hàng - FloraCharm')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-6 font-serif fw-bold">Tra Cứu Tiến Độ Đơn Hàng</h1>
        <p class="text-muted">Nhập Mã đơn hàng và Số điện thoại nhận hoa để theo dõi quá trình cắm và giao hoa.</p>

        <!-- Form Tra Cứu -->
        <div class="mx-auto bg-white p-4 rounded-4 shadow-sm border mt-4" style="max-width: 600px;">
            <form action="{{ route('orders.track') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-6 text-start">
                        <label class="form-label small fw-semibold">Mã đơn hàng</label>
                        <input type="text" name="order_number" value="{{ request('order_number') }}" class="form-control rounded-pill" placeholder="Ví dụ: FLW-2026..." required>
                    </div>
                    <div class="col-md-6 text-start">
                        <label class="form-label small fw-semibold">Số điện thoại nhận</label>
                        <input type="tel" name="phone" value="{{ request('phone') }}" class="form-control rounded-pill" placeholder="Ví dụ: 0912345678" required>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-flower w-100 py-2">
                            <i class="fa-solid fa-magnifying-glass me-2"></i> Tra Cứu Ngay
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Kết Quả Tra Cứu -->
    @if(isset($order) && $order)
        <div class="bg-white p-5 rounded-5 shadow-sm border mx-auto" style="max-width: 800px;">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <span class="text-muted small">Mã đơn hàng:</span>
                    <h5 class="fw-bold text-danger mb-0">{{ $order->order_number }}</h5>
                </div>
                <div class="text-end">
                    <span class="text-muted small">Trạng thái hiện tại:</span>
                    <div>
                        @switch($order->order_status)
                            @case('pending')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fa-solid fa-clock me-1"></i> Chờ Xác Nhận</span>
                                @break
                            @case('processing')
                                <span class="badge bg-info text-dark px-3 py-2 rounded-pill"><i class="fa-solid fa-scissors me-1"></i> Đang Cắm Hoa</span>
                                @break
                            @case('delivering')
                                <span class="badge bg-primary px-3 py-2 rounded-pill"><i class="fa-solid fa-truck-fast me-1"></i> Đang Giao Hoa</span>
                                @break
                            @case('completed')
                                <span class="badge bg-success px-3 py-2 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i> Giao Thành Công</span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger px-3 py-2 rounded-pill"><i class="fa-solid fa-ban me-1"></i> Đã Hủy</span>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>

            <!-- Visual Progress Timeline -->
            @php
                $statuses = ['pending', 'processing', 'delivering', 'completed'];
                $currentIndex = array_search($order->order_status, $statuses);
                if ($currentIndex === false && $order->order_status === 'cancelled') $currentIndex = -1;
            @endphp

            @if($order->order_status !== 'cancelled')
                <div class="position-relative m-4">
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($currentIndex / 3) * 100 }}%;"></div>
                    </div>
                    <div class="d-flex justify-content-between position-absolute top-50 start-0 w-100 translate-middle-y">
                        <button type="button" class="btn btn-sm btn-{{ $currentIndex >= 0 ? 'danger' : 'secondary' }} rounded-pill" style="width: 2.2rem; height:2.2rem;">1</button>
                        <button type="button" class="btn btn-sm btn-{{ $currentIndex >= 1 ? 'danger' : 'secondary' }} rounded-pill" style="width: 2.2rem; height:2.2rem;">2</button>
                        <button type="button" class="btn btn-sm btn-{{ $currentIndex >= 2 ? 'danger' : 'secondary' }} rounded-pill" style="width: 2.2rem; height:2.2rem;">3</button>
                        <button type="button" class="btn btn-sm btn-{{ $currentIndex >= 3 ? 'danger' : 'secondary' }} rounded-pill" style="width: 2.2rem; height:2.2rem;">4</button>
                    </div>
                </div>
                <div class="d-flex justify-content-between small text-center text-muted mt-4 mb-5">
                    <span class="w-25">1. Tiếp Nhận Đơn</span>
                    <span class="w-25">2. Florist Cắm Hoa</span>
                    <span class="w-25">3. Đang Giao Tận Nơi</span>
                    <span class="w-25">4. Đã Giao Hoa</span>
                </div>
            @endif

            <!-- Thông Tin Người Nhận & Danh Sách Hoa -->
            <div class="row g-4 text-start small">
                <div class="col-md-6">
                    <h6 class="fw-bold text-dark border-bottom pb-2">Thông Tin Giao Hàng</h6>
                    <p class="mb-1 text-muted">Người nhận: <strong class="text-dark">{{ $order->receiver_name }}</strong></p>
                    <p class="mb-1 text-muted">Số điện thoại: <strong class="text-dark">{{ $order->receiver_phone }}</strong></p>
                    <p class="mb-1 text-muted">Ngày giao: <strong class="text-dark">{{ $order->delivery_date->format('d/m/Y') }}</strong> ({{ $order->delivery_time_slot }})</p>
                    <p class="mb-1 text-muted">Địa chỉ: <strong class="text-dark">{{ $order->shipping_address }}</strong></p>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold text-dark border-bottom pb-2">Lời Chúc In Trên Thiệp</h6>
                    <div class="p-3 bg-light rounded-3 fst-italic">
                        "{{ $order->card_message ?: 'Không có lời chúc in thiệp.' }}"
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
