@extends('layouts.admin')

@section('title', 'Quản Lý Danh Mục Hoa - FloraCharm Admin')
@section('page_title', 'Danh Mục Sản Phẩm Hoa')

@section('content')
<div class="row g-4">
    <!-- Cột Trái: Thêm Danh Mục Mới -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <h5 class="fw-bold mb-3 font-serif">Thêm Danh Mục Mới</h5>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control rounded-pill @error('name') is-invalid @enderror" placeholder="Ví dụ: Hoa Cưới Cầm Tay" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Biểu tượng FontAwesome</label>
                    <input type="text" name="icon" class="form-control rounded-pill" value="fa-spa" placeholder="fa-spa, fa-heart, fa-cake-candles">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Mô tả ngắn</label>
                    <textarea name="description" rows="3" class="form-control rounded-4" placeholder="Mô tả ý nghĩa danh mục hoa..."></textarea>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" id="catActive" class="form-check-input" value="1" checked>
                    <label class="form-check-label small fw-semibold" for="catActive">Hiển thị trên website</label>
                </div>

                <button type="submit" class="btn btn-danger w-100 rounded-pill py-2">
                    <i class="fa-solid fa-plus me-1"></i> Tạo Danh Mục
                </button>
            </form>
        </div>
    </div>

    <!-- Cột Phải: Danh Sách Danh Mục Hiện Tại -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <h5 class="fw-bold mb-3 font-serif">Danh Sách Danh Mục ({{ $categories->count() }})</h5>
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tên danh mục</th>
                            <th>Đường dẫn (Slug)</th>
                            <th>Số mẫu hoa</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                            <tr>
                                <td>
                                    <i class="fa-solid {{ $cat->icon ?: 'fa-spa' }} text-danger me-2"></i>
                                    <strong>{{ $cat->name }}</strong>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $cat->slug }}</span></td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill">
                                        {{ $cat->products_count }} mẫu hoa
                                    </span>
                                </td>
                                <td>
                                    @if($cat->is_active)
                                        <span class="badge bg-success rounded-pill">Hiển thị</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill">Tạm ẩn</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;" title="Xóa danh mục">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Chưa có danh mục nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection