@extends('layouts.app')

@section('title', 'Đặt Hàng & Thanh Toán - FloraCharm')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <h1 class="display-6 font-serif fw-bold">Thông Tin Giao Hoa & Thanh Toán</h1>
        <p class="text-muted">Vui lòng điền đầy đủ thông tin người nhận để FloraCharm giao hoa tận tay đúng hẹn.</p>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row g-5">
            <!-- Cột Trái: Thông Tin Giao Hàng & Thiệp -->
            <div class="col-lg-8">
                <!-- 1. Thông Tin Người Nhận -->
                <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                    <h5 class="fw-bold mb-4 font-serif text-danger">
                        <i class="fa-solid fa-location-dot me-2"></i> 1. Thông Tin Người Nhận Hoa
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Họ và tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" name="receiver_name" value="{{ old('receiver_name', $user->name ?? '') }}" class="form-control rounded-pill @error('receiver_name') is-invalid @enderror" placeholder="Ví dụ: Nguyễn Thị Mai" required>
                            @error('receiver_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Số điện thoại người nhận <span class="text-danger">*</span></label>
                            <input type="tel" name="receiver_phone" value="{{ old('receiver_phone', $user->phone ?? '') }}" class="form-control rounded-pill @error('receiver_phone') is-invalid @enderror" placeholder="Ví dụ: 0912345678" required>
                            @error('receiver_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Địa chỉ giao hoa chi tiết <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_address" value="{{ old('shipping_address', $user->address ?? '') }}" class="form-control rounded-pill @error('shipping_address') is-invalid @enderror" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố" required>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 2. Thời Gian Giao Hoa & Thiệp Chúc Mừng (Đặc thù hoa) -->
                <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                    <h5 class="fw-bold mb-4 font-serif text-danger">
                        <i class="fa-solid fa-calendar-check me-2"></i> 2. Thời Gian Giao Hoa & Lời Chúc Thiệp
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Ngày giao hoa <span class="text-danger">*</span></label>
                            <input type="date" name="delivery_date" value="{{ old('delivery_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" class="form-control rounded-pill @error('delivery_date') is-invalid @enderror" required>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Khung giờ giao hoa <span class="text-danger">*</span></label>
                            <select name="delivery_time_slot" class="form-select rounded-pill">
                                <option value="Giao càng sớm càng tốt (Hỏa tốc 60p)">Giao càng sớm càng tốt (Hỏa tốc 60p)</option>
                                <option value="08:00 - 10:00 (Sáng sớm)">08:00 - 10:00 (Sáng sớm)</option>
                                <option value="10:00 - 12:00 (Buổi trưa)">10:00 - 12:00 (Buổi trưa)</option>
                                <option value="14:00 - 17:00 (Buổi chiều)">14:00 - 17:00 (Buổi chiều)</option>
                                <option value="17:00 - 20:00 (Buổi tối)">17:00 - 20:00 (Buổi tối)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Nội dung in thiệp chúc mừng (FloraCharm tặng miễn phí)</label>
                            <textarea name="card_message" rows="3" class="form-control rounded-4" placeholder="Ví dụ: Chúc em yêu sinh nhật ngập tràn hạnh phúc và mãi xinh tươi như những đóa hoa này!">{{ old('card_message') }}</textarea>
                            <small class="text-muted">Florist sẽ in thiệp trang nhã và gài kèm vào bó hoa của bạn.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Ghi chú cho shipper (Tùy chọn)</label>
                            <input type="text" name="note" value="{{ old('note') }}" class="form-control rounded-pill" placeholder="Ví dụ: Gọi trước khi đến 15 phút, gửi lễ tân...">
                        </div>
                    </div>
                </div>

                <!-- 3. Phương Thức Thanh Toán -->
                <div class="bg-white p-4 rounded-4 shadow-sm border">
                    <h5 class="fw-bold mb-4 font-serif text-danger">
                        <i class="fa-solid fa-credit-card me-2"></i> 3. Phương Thức Thanh Toán
                    </h5>

                    <div class="form-check p-3 border rounded-4 mb-3 d-flex align-items-center">
                        <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_bank" value="bank_transfer" checked>
                        <label class="form-check-label w-100" for="pay_bank">
                            <div class="fw-bold"><i class="fa-solid fa-qrcode text-danger me-2"></i> Chuyển Khoản Ngân Hàng Tự Động (Quét Mã VietQR) <span class="badge bg-success small">Khuyên Dùng</span></div>
                            <small class="text-muted">Hệ thống sẽ sinh mã QR chuẩn VietQR có sẵn số tiền và nội dung đơn hàng ngay sau khi bấm đặt.</small>
                        </label>
                    </div>

                    <div class="form-check p-3 border rounded-4 d-flex align-items-center">
                        <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_cod" value="cod">
                        <label class="form-check-label w-100" for="pay_cod">
                            <div class="fw-bold"><i class="fa-solid fa-money-bill-wave text-danger me-2"></i> Thanh Toán Khi Nhận Hoa (COD)</div>
                            <small class="text-muted">Người nhận hoặc người đặt thanh toán tiền mặt trực tiếp cho nhân viên giao hoa.</small>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Cột Phải: Tóm Tắt Đơn Hàng -->
            <div class="col-lg-4">
                <div class="bg-white p-4 rounded-4 shadow-sm border sticky-top" style="top: 100px;">
                    <h5 class="fw-bold mb-4 font-serif">Đơn Hàng Của Bạn</h5>

                    <div class="mb-3">
                        @foreach($cart as $item)
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $item['thumbnail'] }}" alt="{{ $item['name'] }}" class="rounded-3 me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                <div class="flex-grow-1 small">
                                    <div class="fw-bold text-truncate" style="max-width: 170px;">{{ $item['name'] }}</div>
                                    <div class="text-muted">{{ $item['quantity'] }} x {{ number_format($item['price']) }} đ</div>
                                </div>
                                <div class="fw-bold text-end small">
                                    {{ number_format($item['price'] * $item['quantity']) }} đ
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-3">

                    <!-- Mã giảm giá -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Mã giảm giá (Nếu có)</label>
                        <div class="input-group">
                            <input type="text" name="coupon_code" class="form-control rounded-start-pill text-uppercase" placeholder="Mã voucher...">
                            <button type="button" class="btn btn-outline-secondary rounded-end-pill">Áp dụng</button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>Tạm tính tiền hoa:</span>
                        <span class="fw-bold text-dark">{{ number_format($subtotal) }} đ</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>Phí giao hoa:</span>
                        <span class="text-success fw-bold">Miễn phí</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>Thiệp chúc mừng:</span>
                        <span class="text-success fw-bold">Tặng miễn phí</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Tổng thanh toán:</span>
                        <span class="fw-bold text-danger fs-4">{{ number_format($subtotal) }} đ</span>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-flower btn-lg py-3">
                            <i class="fa-solid fa-lock me-2"></i> Xác Nhận Đặt Hoa
                        </button>
                    </div>

                    <small class="text-muted text-center d-block mt-3" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-shield-halved text-success me-1"></i> Thông tin thanh toán được bảo mật an toàn 100%.
                    </small>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
