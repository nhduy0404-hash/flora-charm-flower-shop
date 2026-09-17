@extends('layouts.app')

@section('title', 'Giỏ Hàng Của Bạn - FloraCharm')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <h1 class="display-6 font-serif fw-bold">Giỏ Hàng Của Bạn</h1>
        <p class="text-muted">Kiểm tra lại danh sách hoa trước khi tiến hành chọn thời gian và đặt hàng.</p>
    </div>

    @if(!empty($cart) && count($cart) > 0)
        <div class="row g-5">
            <!-- Danh Sách Sản Phẩm -->
            <div class="col-lg-8">
                <div class="bg-white rounded-4 shadow-sm border p-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm hoa</th>
                                    <th>Đơn giá</th>
                                    <th style="width: 130px;">Số lượng</th>
                                    <th>Tạm tính</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ str_starts_with($item['thumbnail'], 'http') ? $item['thumbnail'] : asset('storage/' . $item['thumbnail']) }}" alt="{{ $item['name'] }}" class="rounded-3 me-3" style="width: 70px; height: 70px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0 fw-bold">
                                                        <a href="{{ route('flowers.show', $item['slug']) }}" class="text-decoration-none text-dark">
                                                            {{ $item['name'] }}
                                                        </a>
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-semibold">{{ number_format($item['price']) }} đ</td>
                                        <td>
                                            <form action="{{ route('cart.update', $id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="input-group input-group-sm">
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control text-center" onchange="this.form.submit()">
                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-danger fw-bold">{{ number_format($item['price'] * $item['quantity']) }} đ</td>
                                        <td>
                                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Xóa">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                        <a href="{{ route('flowers.index') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục chọn thêm hoa
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tóm Tắt Đơn Hàng & Nút Thanh Toán -->
            <div class="col-lg-4">
                <div class="bg-white rounded-4 shadow-sm border p-4 sticky-top" style="top: 100px;">
                    <h5 class="fw-bold mb-4 font-serif">Tóm Tắt Đơn Hàng</h5>

                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Tổng tiền hoa:</span>
                        <span class="fw-bold text-dark fs-5">{{ number_format($subtotal) }} đ</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success fw-semibold">Miễn phí giao hàng</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Thiệp chúc mừng:</span>
                        <span class="text-success fw-semibold">Tặng kèm miễn phí</span>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Tổng thanh toán:</span>
                        <span class="fw-bold text-danger fs-4">{{ number_format($subtotal) }} đ</span>
                    </div>

                    <div class="d-grid">
                        <a href="{{ route('checkout.index') }}" class="btn btn-flower btn-lg py-3 text-center">
                            Tiến Hành Đặt Hàng <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white p-5 rounded-4 text-center border shadow-sm my-4">
            <i class="fa-solid fa-cart-shopping text-muted fs-1 mb-3"></i>
            <h4 class="fw-bold mb-2">Giỏ hàng của bạn đang trống</h4>
            <p class="text-muted mb-4">Hãy chọn những bó hoa tươi thắm nhất dành tặng những người bạn yêu quý nhé!</p>
            <a href="{{ route('flowers.index') }}" class="btn btn-flower px-4 py-2">
                Khám Phá Hoa Ngay
            </a>
        </div>
    @endif
</div>
@endsection
