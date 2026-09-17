@extends('layouts.admin')

@section('title', 'Quản Lý Sản Phẩm Hoa - FloraCharm Admin')
@section('page_title', 'Danh Sách Sản Phẩm Hoa')

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1">Hệ Thống Quản Trị Danh Mục Hoa</h5>
            <p class="text-muted small mb-0">Quản lý tồn kho, định giá và cơ chế bảo toàn dữ liệu giao dịch (Soft Deletes).</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-danger rounded-pill px-4">
            <i class="fa-solid fa-plus me-1"></i> Thêm Mẫu Hoa Mới
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Navigation Tabs: Active vs Trashed -->
    <ul class="nav nav-tabs mb-4 border-bottom">
        <li class="nav-item">
            <a class="nav-link {{ request('status') !== 'trashed' ? 'active fw-bold text-danger' : 'text-secondary' }}" 
               href="{{ route('admin.products.index') }}">
                <i class="fa-solid fa-boxes-stacked me-1"></i> Đang Kinh Doanh
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') === 'trashed' ? 'active fw-bold text-danger' : 'text-secondary' }}" 
               href="{{ route('admin.products.index', ['status' => 'trashed']) }}">
                <i class="fa-solid fa-trash-can me-1"></i> Thùng Rác (Xóa Mềm)
                @if($trashedCount > 0)
                    <span class="badge bg-secondary ms-1">{{ $trashedCount }}</span>
                @endif
            </a>
        </li>
    </ul>

    <!-- Filter & Search Bar -->
    <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2 mb-4">
        @if(request('status') === 'trashed')
            <input type="hidden" name="status" value="trashed">
        @endif
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control border-start-0" placeholder="Tìm theo tên sản phẩm hoa...">
            </div>
        </div>
        <div class="col-md-4">
            <select name="category_id" class="form-select">
                <option value="">-- Tất cả danh mục --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary w-100"><i class="fa-solid fa-filter me-1"></i> Lọc</button>
            @if(request()->hasAny(['keyword', 'category_id']))
                <a href="{{ route('admin.products.index', request('status') === 'trashed' ? ['status' => 'trashed'] : []) }}" class="btn btn-light border" title="Xóa lọc">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </div>
    </form>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th style="width: 70px;">Hình ảnh</th>
                    <th>Tên hoa</th>
                    <th>Danh mục</th>
                    <th>Giá niêm yết</th>
                    <th>Giá KM</th>
                    <th>Tồn kho</th>
                    <th>Trạng thái</th>
                    @if(request('status') === 'trashed')
                        <th>Ngày xóa</th>
                        <th class="text-end">Khôi phục / Xóa vĩnh viễn</th>
                    @else
                        <th>Nổi bật</th>
                        <th class="text-end">Thao tác</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                    <tr class="{{ request('status') === 'trashed' ? 'table-light opacity-75' : '' }}">
                        <td>
                            <img src="{{ $p->primary_image }}" alt="{{ $p->name }}" class="rounded-3" style="width: 55px; height: 55px; object-fit: cover;">
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
                            @if(request('status') === 'trashed')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Đã xóa mềm</span>
                            @else
                                @if($p->is_active)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Đang Bán</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border rounded-pill">Tạm Ẩn</span>
                                @endif
                            @endif
                        </td>

                        @if(request('status') === 'trashed')
                            <td class="small text-muted">
                                {{ $p->deleted_at ? $p->deleted_at->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <form action="{{ route('admin.products.restore', $p->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Khôi phục lại danh mục bán">
                                            <i class="fa-solid fa-rotate-left me-1"></i> Khôi phục
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.products.forceDelete', $p->id) }}" method="POST" 
                                          onsubmit="return confirm('CẢNH BÁO TOÀN VẸN DỮ LIỆU:\nXóa vĩnh viễn sẽ xóa hoàn toàn khỏi Database!\nChỉ thành công nếu mẫu hoa này CHƯA TỪNG có đơn hàng nào trong lịch sử.\nBạn có chắc chắn muốn xóa vĩnh viễn?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa vĩnh viễn khỏi Database">
                                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Xóa hẳn
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @else
                            <td>
                                @if($p->is_featured)
                                    <i class="fa-solid fa-star text-warning" title="Nổi bật"></i>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-sm btn-outline-primary" title="Chỉnh sửa thông tin">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" 
                                          onsubmit="return confirm('Bạn có chắc muốn đưa mẫu hoa này vào thùng rác?\n(Dữ liệu đơn hàng trong quá khứ vẫn được bảo toàn nguyên vẹn)');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Đưa vào thùng rác (Xóa mềm)">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fa-solid fa-box-open fs-3 d-block mb-2 text-secondary"></i>
                            {{ request('status') === 'trashed' ? 'Thùng rác trống. Không có sản phẩm nào bị xóa mềm.' : 'Không tìm thấy sản phẩm hoa nào.' }}
                        </td>
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
