@extends('layouts.app')

@section('title', 'Bộ Sưu Tập Hoa Tươi - FloraCharm')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb & Tiêu đề -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang Chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bộ Sưu Tập Hoa Tươi</li>
            </ol>
        </nav>
        <h1 class="display-6 font-serif fw-bold">Bộ Sưu Tập Hoa Tươi</h1>
        <p class="text-muted">Khám phá hơn 100+ mẫu hoa tươi được thiết kế tỉ mỉ cho mọi khoảnh khắc ý nghĩa.</p>
    </div>

    <div class="row g-4">
        <!-- Cột Trái: Bộ Lọc (Filter Sidebar) -->
        <div class="col-lg-3">
            <div class="bg-white p-4 rounded-4 shadow-sm border">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-filter me-2 text-danger"></i> Bộ Lọc Tìm Kiếm</h5>

                <form action="{{ route('flowers.index') }}" method="GET">
                    <!-- Tìm kiếm từ khóa -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Từ khóa tìm kiếm</label>
                        <div class="input-group">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-start-pill" placeholder="Ví dụ: Hoa hồng, lan...">
                            <button class="btn btn-outline-danger rounded-end-pill" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>

                    <!-- Danh mục -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Chủ đề / Danh mục</label>
                        <select name="category" class="form-select rounded-pill">
                            <option value="">-- Tất cả danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Khoảng giá -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Mức giá</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price_range" id="price_all" value="" {{ !request('price_range') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="price_all">Tất cả mức giá</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price_range" id="price_under_500" value="under_500" {{ request('price_range') == 'under_500' ? 'checked' : '' }}>
                            <label class="form-check-label small" for="price_under_500">Dưới 500.000 đ</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price_range" id="price_500_1000" value="500_1000" {{ request('price_range') == '500_1000' ? 'checked' : '' }}>
                            <label class="form-check-label small" for="price_500_1000">Từ 500.000 - 1.000.000 đ</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price_range" id="price_above_1000" value="above_1000" {{ request('price_range') == 'above_1000' ? 'checked' : '' }}>
                            <label class="form-check-label small" for="price_above_1000">Trên 1.000.000 đ</label>
                        </div>
                    </div>

                    <!-- Sắp xếp -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Sắp xếp theo</label>
                        <select name="sort" class="form-select rounded-pill">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến cao</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao xuống thấp</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-flower">Áp Dụng Bộ Lọc</button>
                        <a href="{{ route('flowers.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm">Đặt lại</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cột Phải: Lưới Sản Phẩm -->
        <div class="col-lg-9">
            @if($products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $flower)
                        <div class="col-6 col-md-4">
                            <div class="flower-card h-100 d-flex flex-column">
                                <div class="position-relative">
                                    <a href="{{ route('flowers.show', $flower->slug) }}">
                                        <img src="{{ $flower->primary_image }}" alt="{{ $flower->name }}">
                                    </a>
                                    @if($flower->sale_price && $flower->sale_price < $flower->price)
                                        <span class="position-absolute top-0 start-0 bg-danger text-white rounded-end px-2 py-1 small fw-bold mt-2">
                                            -{{ round((($flower->price - $flower->sale_price) / $flower->price) * 100) }}%
                                        </span>
                                    @endif
                                </div>
                                <div class="p-3 d-flex flex-column flex-grow-1">
                                    <small class="text-muted mb-1">{{ $flower->category->name ?? 'Hoa tươi' }}</small>
                                    <h6 class="fw-bold mb-2">
                                        <a href="{{ route('flowers.show', $flower->slug) }}" class="text-decoration-none text-dark text-truncate d-block">
                                            {{ $flower->name }}
                                        </a>
                                    </h6>
                                    <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                                        <div>
                                            @if($flower->sale_price)
                                                <span class="text-danger fw-bold fs-6">{{ number_format($flower->sale_price) }} đ</span>
                                                <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.75rem;">
                                                    {{ number_format($flower->price) }} đ
                                                </small>
                                            @else
                                                <span class="text-danger fw-bold fs-6">{{ number_format($flower->price) }} đ</span>
                                            @endif
                                        </div>
                                        <button onclick="addToCartAjax({{ $flower->id }})" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Thêm vào giỏ">
                                            <i class="fa-solid fa-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Phân Trang (Pagination) -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="bg-white p-5 rounded-4 text-center border">
                    <i class="fa-solid fa-seedling text-muted fs-1 mb-3"></i>
                    <h5 class="fw-bold">Không tìm thấy sản phẩm hoa phù hợp!</h5>
                    <p class="text-muted">Vui lòng thử điều chỉnh lại bộ lọc hoặc từ khóa tìm kiếm.</p>
                    <a href="{{ route('flowers.index') }}" class="btn btn-flower">Xem Tất Cả Hoa</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
