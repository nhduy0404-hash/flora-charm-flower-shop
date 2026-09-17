@extends('layouts.admin')

@section('title', 'Thêm Mẫu Hoa Mới - FloraCharm Admin')
@section('page_title', 'Thêm Mẫu Hoa Tươi Mới')

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white p-4 mx-auto" style="max-width: 800px;">
    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold">Tên mẫu hoa <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control rounded-pill @error('name') is-invalid @enderror" placeholder="Ví dụ: Bó Hoa Cúc Họa Mi Thuần Khiết" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select rounded-pill" required>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Giá bán niêm yết (VNĐ) <span class="text-danger">*</span></label>
                <input type="number" name="price" value="{{ old('price') }}" class="form-control rounded-pill @error('price') is-invalid @enderror" placeholder="450000" required>
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Giá khuyến mãi (Nếu có)</label>
                <input type="number" name="sale_price" value="{{ old('sale_price') }}" class="form-control rounded-pill @error('sale_price') is-invalid @enderror" placeholder="399000">
                @error('sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Số lượng tồn kho <span class="text-danger">*</span></label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 10) }}" class="form-control rounded-pill" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Link ảnh đại diện hoa (URL ảnh)</label>
                <input type="url" name="thumbnail" value="{{ old('thumbnail') }}" class="form-control rounded-pill" placeholder="https://images.unsplash.com/...">
                <small class="text-muted">Nhập link ảnh từ Unsplash hoặc link CDN trực tiếp.</small>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Mô tả chi tiết & ý nghĩa hoa</label>
                <textarea name="description" rows="4" class="form-control rounded-4" placeholder="Mô tả các loài hoa có trong bó, ý nghĩa và thông điệp gửi gắm...">{{ old('description') }}</textarea>
            </div>

            <div class="col-12">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="featCheck" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="featCheck">Đặt làm hoa nổi bật (Hiện trang chủ)</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="is_active" id="activeCheck" value="1" checked>
                    <label class="form-check-label fw-semibold" for="activeCheck">Đang mở bán</label>
                </div>
            </div>

            <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Hủy bỏ</a>
                <button type="submit" class="btn btn-danger rounded-pill px-4">Lưu Mẫu Hoa Mới</button>
            </div>
        </div>
    </form>
</div>
@endsection
