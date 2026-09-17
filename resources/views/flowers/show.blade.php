@extends('layouts.app')

@section('title', $product->name . ' - FloraCharm')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang Chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('flowers.index') }}" class="text-decoration-none text-muted">Hoa Tươi</a></li>
            <li class="breadcrumb-item"><a href="{{ route('flowers.index', ['category' => $product->category->slug ?? '']) }}" class="text-decoration-none text-muted">{{ $product->category->name ?? 'Chủ đề' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Ảnh Lớn Sản Phẩm -->
        <div class="col-lg-6">
            <div class="bg-white p-3 rounded-5 shadow-sm border text-center">
                <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}" class="img-fluid rounded-4" style="max-height: 520px; width: 100%; object-fit: cover;">
            </div>
        </div>

        <!-- Thông Tin Chi Tiết & Nút Mua -->
        <div class="col-lg-6">
            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1 mb-2 fw-semibold">
                {{ $product->category->name ?? 'Hoa tươi nghệ thuật' }}
            </span>
            <h1 class="display-6 font-serif fw-bold mb-3">{{ $product->name }}</h1>

            <!-- Giá Bán -->
            <div class="p-3 bg-white rounded-4 border mb-4 d-flex align-items-baseline gap-3">
                @if($product->sale_price && $product->sale_price < $product->price)
                    <span class="text-danger fw-bold fs-3">{{ number_format($product->sale_price) }} đ</span>
                    <span class="text-muted text-decoration-line-through fs-5">{{ number_format($product->price) }} đ</span>
                    <span class="badge bg-danger rounded-pill ms-auto">Tiết kiệm {{ number_format($product->price - $product->sale_price) }} đ</span>
                @else
                    <span class="text-danger fw-bold fs-3">{{ number_format($product->price) }} đ</span>
                @endif
            </div>

            <!-- Tình trạng kho & Vận chuyển -->
            <div class="mb-4 small">
                <div class="d-flex align-items-center mb-2">
                    <i class="fa-solid fa-circle-check text-success me-2"></i>
                    <span>Tình trạng: <strong>{{ $product->stock_quantity > 0 ? 'Còn hàng trong kho (' . $product->stock_quantity . ' bó)' : 'Hết hàng' }}</strong></span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="fa-solid fa-truck-fast text-danger me-2"></i>
                    <span>Giao hàng: <strong>Hỏa tốc 60 phút tại Hà Nội & TP.HCM</strong></span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-gift text-danger me-2"></i>
                    <span>Tặng kèm: <strong>Thiệp mừng viết tay + Banner chúc mừng</strong></span>
                </div>
            </div>

            <hr class="my-4">

            <!-- Chọn Số Lượng & Nút Thêm Vào Giỏ -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="input-group" style="width: 140px;">
                    <button class="btn btn-outline-secondary" type="button" onclick="changeQty(-1)">-</button>
                    <input type="number" id="buyQuantity" class="form-control text-center fw-bold" value="1" min="1" max="{{ $product->stock_quantity }}">
                    <button class="btn btn-outline-secondary" type="button" onclick="changeQty(1)">+</button>
                </div>
                <button type="button" onclick="addWithCustomQty()" class="btn btn-flower flex-grow-1 py-3">
                    <i class="fa-solid fa-cart-plus me-2"></i> Thêm Vào Giỏ Hàng
                </button>
            </div>

            <!-- Mô Tả Ý Nghĩa Bó Hoa -->
            <div class="bg-white p-4 rounded-4 border">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-book-open me-2 text-danger"></i> Ý Nghĩa & Mô Tả Bó Hoa</h6>
                <p class="text-muted mb-0" style="line-height: 1.8;">
                    {{ $product->description ?: 'Bó hoa được các nghệ nhân Florist cắm thủ công từ những đóa hoa tuyển chọn tươi nhất trong ngày. Thiết kế trang nhã, màu sắc hài hòa thích hợp gửi gắm yêu thương, sự trân trọng và chúc mừng đến những người quan trọng trong cuộc đời bạn.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Hoa Cùng Danh Mục (Related Flowers) -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="mt-5 pt-5 border-top">
            <h3 class="font-serif fw-bold mb-4">Mẫu Hoa Cùng Chủ Đề Bạn Có Thể Thích</h3>
            <div class="row g-4">
                @foreach($relatedProducts as $related)
                    <div class="col-6 col-md-3">
                        <div class="flower-card h-100 d-flex flex-column">
                            <a href="{{ route('flowers.show', $related->slug) }}">
                                <img src="{{ $related->thumbnail }}" alt="{{ $related->name }}">
                            </a>
                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <h6 class="fw-bold mb-2">
                                    <a href="{{ route('flowers.show', $related->slug) }}" class="text-decoration-none text-dark text-truncate d-block">
                                        {{ $related->name }}
                                    </a>
                                </h6>
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <span class="text-danger fw-bold">{{ number_format($related->sale_price ?: $related->price) }} đ</span>
                                    <button onclick="addToCartAjax({{ $related->id }})" class="btn btn-sm btn-outline-danger rounded-circle p-2">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function changeQty(delta) {
        const input = document.getElementById('buyQuantity');
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        input.value = val;
    }

    function addWithCustomQty() {
        const qty = parseInt(document.getElementById('buyQuantity').value) || 1;
        addToCartAjax({{ $product->id }}, qty);
    }
</script>
@endsection
