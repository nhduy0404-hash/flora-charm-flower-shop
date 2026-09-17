@extends('layouts.app')

@section('title', 'FloraCharm - Shop Hoa Tươi Cao Cấp & Giao Hoa Hỏa Tốc')

@section('content')
    <!-- Hero Banner -->
    <section class="py-5" style="background: linear-gradient(135deg, #fff0f3 0%, #fcf6f5 100%);">
        <div class="container py-lg-4">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <span class="badge bg-danger text-white rounded-pill px-3 py-2 mb-3 fw-semibold">
                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Bộ Sưu Tập Mùa Yêu Thương 2026
                    </span>
                    <h1 class="display-4 fw-bold mb-3 font-serif text-dark">
                        Gửi Trọn Yêu Thương Trên Từng Cánh Hoa
                    </h1>
                    <p class="lead text-muted mb-4">
                        Mỗi bó hoa là một thông điệp chân thành. FloraCharm cam kết mang đến những tác phẩm hoa tươi nghệ thuật, thiết kế tinh tế và giao tận tay người thương đúng hẹn.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('flowers.index') }}" class="btn btn-flower btn-lg px-4 py-3">
                            <i class="fa-solid fa-basket-shopping me-2"></i> Khám Phá Bộ Sưu Tập
                        </a>
                        <a href="{{ route('flowers.index', ['category' => 'hoa-sinh-nhat']) }}" class="btn btn-outline-dark btn-lg px-4 py-3 rounded-pill fw-semibold">
                            Hoa Sinh Nhật Hot
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="position-relative d-inline-block">
                        <img src="https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=800&q=80" 
                             alt="Bó hoa tươi FloraCharm" 
                             class="img-fluid rounded-5 shadow-lg" 
                             style="max-height: 480px; width: 100%; object-fit: cover;">
                        <div class="position-absolute bottom-0 start-0 bg-white p-3 rounded-4 shadow m-3 text-start d-none d-sm-block">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger text-white rounded-circle p-2 me-3">
                                    <i class="fa-solid fa-heart fs-5"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">100% Hoa Tươi Mới</div>
                                    <small class="text-muted">Nhập trực tiếp từ Đà Lạt & Ecuador</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3 Điểm Nổi Bật (Service Highlights) -->
    <section class="py-4 bg-white border-bottom">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-truck-fast text-danger fs-1 me-3"></i>
                        <div class="text-start">
                            <h6 class="fw-bold mb-1">Giao Hoa Hỏa Tốc 60 Phút</h6>
                            <small class="text-muted">Giao tận tay đúng giờ cam kết toàn quốc</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 border-start-md">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-camera text-danger fs-1 me-3"></i>
                        <div class="text-start">
                            <h6 class="fw-bold mb-1">Chụp Ảnh Duyệt Trước Khi Giao</h6>
                            <small class="text-muted">Đảm bảo hoa thật đẹp giống hình 100%</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 border-start-md">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-gift text-danger fs-1 me-3"></i>
                        <div class="text-start">
                            <h6 class="fw-bold mb-1">Tặng Thiệp & Banner Miễn Phí</h6>
                            <small class="text-muted">Viết lời chúc ý nghĩa theo yêu cầu riêng</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Danh Mục Hoa (Categories) -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-danger fw-bold text-uppercase small">Chủ Đề Yêu Thích</span>
                <h2 class="display-6 fw-bold font-serif">Chọn Hoa Theo Dịp</h2>
                <div class="mx-auto bg-danger mt-2" style="width: 50px; height: 3px; border-radius: 2px;"></div>
            </div>

            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-6 col-md-4 col-lg-2 text-center">
                        <a href="{{ route('flowers.index', ['category' => $category->slug]) }}" class="text-decoration-none text-dark d-block">
                            <div class="rounded-circle overflow-hidden mx-auto mb-3 shadow-sm border border-3 border-white" style="width: 130px; height: 130px;">
                                <img src="{{ $category->image ?? 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?w=400&q=80' }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-100 h-100 object-fit-cover" style="transition: transform 0.3s ease;">
                            </div>
                            <h6 class="fw-bold mb-1">{{ $category->name }}</h6>
                            <small class="text-muted">Xem ngay <i class="fa-solid fa-arrow-right small"></i></small>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Hoa Nổi Bật (Featured Flowers) -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <span class="text-danger fw-bold text-uppercase small">Được Yêu Thích Nhất</span>
                    <h2 class="display-6 fw-bold font-serif mb-0">Mẫu Hoa Nổi Bật</h2>
                </div>
                <a href="{{ route('flowers.index') }}" class="btn btn-outline-danger rounded-pill px-4">
                    Xem Tất Cả <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @foreach($featuredProducts as $flower)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="flower-card h-100 d-flex flex-column">
                            <div class="position-relative">
                                <a href="{{ route('flowers.show', $flower->slug) }}">
                                    <img src="{{ $flower->thumbnail }}" alt="{{ $flower->name }}">
                                </a>
                                @if($flower->sale_price && $flower->sale_price < $flower->price)
                                    <span class="position-absolute top-0 start-0 bg-danger text-white rounded-end px-2 py-1 small fw-bold mt-2">
                                        GIẢM {{ round((($flower->price - $flower->sale_price) / $flower->price) * 100) }}%
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
        </div>
    </section>

    <!-- Banner Khuyến Mãi & Hướng Dẫn Đặt Nhanh -->
    <section class="py-5" style="background: url('https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=1600&q=80') center/cover fixed; position: relative;">
        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.6);"></div>
        <div class="container position-relative py-5 text-center text-white">
            <h2 class="display-5 font-serif fw-bold mb-3">Bạn Cần Tư Vấn Bó Hoa Riêng Cho Người Thương?</h2>
            <p class="lead mb-4 mx-auto" style="max-width: 600px;">
                Đội ngũ Florist chuyên nghiệp của FloraCharm sẵn sàng thiết kế mẫu hoa độc bản theo ý bạn, từ màu sắc, kích thước đến loại hoa yêu thích.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="tel:0988888888" class="btn btn-flower btn-lg px-4">
                    <i class="fa-solid fa-phone me-2"></i> Gọi Hotline: 0988.888.888
                </a>
                <a href="{{ route('orders.track') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                    Tra Cứu Đơn Hàng
                </a>
            </div>
        </div>
    </section>
@endsection
