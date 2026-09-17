@extends('layouts.admin')

@section('title', 'Quản Lý Sản Phẩm Hoa - FloraCharm Admin')
@section('page_title', 'Danh Sách Sản Phẩm Hoa')

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Tất Cả Mẫu Hoa</h5>
        <a href="{{ route('admin.products.create') }}" class="btn btn-danger rounded-pill px-4">
            <i class="fa-solid fa-plus me-1"></i> Thêm Mẫu Hoa Mới
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th style="width: 80px;">Hình ảnh</th>
                    <th>Tên hoa</th>
                    <th>Danh mục</th>
                    <th>Giá niêm yết</th>
                    <th>Giá KM</th>
                    <th>Tồn kho</th>
                    <th>Nổi bật</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                    <tr>
                        <td>
                            <img src="{{ $p->thumbnail }}" alt="{{ $p->name }}" class="rounded-3" style="width: 55px; height: 55px; object-fit: cover;">
                        </td>
                        <td>
                            <strong>{{ $p->name }}</strong>
                            <div class="text-muted small">Slug: {{ $p->slug }}</div>
                        </td>
                        <td><span class="badge bg-secondary rounded-pill">{{ $p->category->name ?? 'N/A' }}</span></td>
                        <td class="fw-semibold">{{ number_format($p->price) }} đ</td>
                        <td>
                            @if($p->sale_price)
                                <span class="text-danger fw-bold">{{ number_format($p->sale_price) }} đ</span>
                            @else
                                <span class="text-muted small">Không</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $p->stock_quantity > 5 ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill">
                                {{ $p->stock_quantity }} bó
                            </span>
                        </td>
                        <td>
                            @if($p->is_featured)
                                <i class="fa-solid fa-star text-warning" title="Nổi bật"></i>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mẫu hoa này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="Xóa">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Chưa có sản phẩm nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="mt-3 d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
