@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng #' . $order->order_number . ' - FloraCharm Admin')
@section('page_title', 'Chi Tiết Đơn Hàng #' . $order->order_number)

@section('content')
<div class="row g-4">
    <!-- Cột Trái: Danh Sách Hoa Đã Đặt & Lời Chúc Thiệp -->
    <div class="col-lg-8">
        <!-- Danh Sách Sản Phẩm -->
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
            <h5 class="fw-bold mb-3 font-serif">Sản Phẩm Trong Đơn Hàng</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tên mẫu hoa</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $item->product_name }}</div>
                                </td>
                                <td>{{ number_format($item->unit_price) }} đ</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end fw-bold text-danger">{{ number_format($item->total_price) }} đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end text-muted">Tạm tính tiền hoa:</td>
                            <td class="text-end fw-bold">{{ number_format($order->subtotal) }} đ</td>
                        </tr>
                        @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="3" class="text-end text-muted">Giảm giá voucher:</td>
                                <td class="text-end text-danger">-{{ number_format($order->discount_amount) }} đ</td>
                            </tr>
                        @endif
                        <tr class="fs-5">
                            <td colspan="3" class="text-end fw-bold">Tổng đơn thanh toán:</td>
                            <td class="text-end fw-bold text-danger">{{ number_format($order->total_amount) }} đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Thiệp Chúc Mừng & Ghi Chú Giao Hoa -->
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <h5 class="fw-bold mb-3 font-serif text-danger"><i class="fa-solid fa-gift me-2"></i> Lời Chúc In Trên Thiệp & Banner</h5>
            <div class="p-3 bg-light rounded-3 fst-italic fs-6 border mb-3">
                "{{ $order->card_message ?: 'Khách hàng không yêu cầu in thiệp.' }}"
            </div>

            @if($order->note)
                <h6 class="fw-bold mb-1">Ghi chú thêm:</h6>
                <p class="text-muted small mb-0">{{ $order->note }}</p>
            @endif
        </div>
    </div>

    <!-- Cột Phải: Cập Nhật Trạng Thái & Thông Tin Giao Hàng -->
    <div class="col-lg-4">
        <!-- Form Cập Nhật Trạng Thái -->
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 font-serif">Cập Nhật Trạng Thái</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>

            @if($order->order_status === 'cancelled')
                <div class="alert alert-warning rounded-4 small p-3 mb-3">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Đơn hàng này đã bị hủy. Số lượng hoa đã được tự động hoàn lại kho.
                </div>
            @endif

            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Tiến độ cắm & giao hoa</label>
                    <select name="order_status" class="form-select rounded-pill">
                        <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>1. Chờ duyệt (Pending)</option>
                        <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>2. Đang cắm hoa (Processing)</option>
                        <option value="delivering" {{ $order->order_status == 'delivering' ? 'selected' : '' }}>3. Đang giao hoa (Delivering)</option>
                        <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>4. Đã giao thành công (Completed)</option>
                        <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>5. Đã hủy đơn (Cancelled)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Tình trạng thanh toán</label>
                    <select name="payment_status" class="form-select rounded-pill">
                        <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán đủ</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 mb-2">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cập Nhật
                </button>
            </form>

            @if($order->payment_status === 'unpaid')
                <form action="{{ route('admin.orders.quickMarkPaid', $order->id) }}" method="POST" onsubmit="return confirm('Xác nhận bạn đã nhận được số tiền {{ number_format($order->total_amount) }} đ cho đơn này?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-success w-100 rounded-pill py-2 small">
                        <i class="fa-solid fa-check-double me-1"></i> Đã Thu Tiền (Xác Nhận Nhanh)
                    </button>
                </form>
            @endif
        </div>

        <!-- Thông Tin Người Nhận -->
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <h5 class="fw-bold mb-3 font-serif">Thông Tin Giao Hàng</h5>
            <div class="small">
                <p class="mb-2"><strong>Người nhận:</strong> {{ $order->receiver_name }}</p>
                <p class="mb-2"><strong>Số điện thoại:</strong> {{ $order->receiver_phone }}</p>
                <p class="mb-2"><strong>Ngày giao:</strong> {{ $order->delivery_date->format('d/m/Y') }}</p>
                <p class="mb-2"><strong>Khung giờ:</strong> {{ $order->delivery_time_slot }}</p>
                <p class="mb-2"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                <p class="mb-0"><strong>Phương thức:</strong> {{ strtoupper($order->payment_method) }}</p>
            </div>
            <hr>
            <a href="javascript:window.print()" class="btn btn-outline-secondary w-100 rounded-pill btn-sm">
                <i class="fa-solid fa-print me-1"></i> In Phiếu Giao Hoa
            </a>
        </div>
    </div>
</div>
@endsection
