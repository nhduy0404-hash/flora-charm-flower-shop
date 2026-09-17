@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Mẫu Hoa - FloraCharm Admin')
@section('page_title', 'Chỉnh Sửa Mẫu Hoa: ' . $product->name)

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white p-4 mx-auto" style="max-width: 850px;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h5 class="fw-bold mb-0 font-serif">Cập Nhật Thông Tin Sản Phẩm</h5>
            <small class="text-muted">Mã ID: #{{ $product->id }} | Slug: {{ $product->slug }}</small>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold">Tên mẫu hoa <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control rounded-pill @error('name') is-invalid @enderror" placeholder="Ví dụ: Bó Hoa Hồng Juliet Quý Phái" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select rounded-pill" required>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ old('category_id', $product->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Giá niêm yết (VNĐ) <span class="text-danger">*</span></label>
                <input type="number" name="price" value="{{ old('price', (int)$product->price) }}" class="form-control rounded-pill @error('price') is-invalid @enderror" required>
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Giá khuyến mãi (VNĐ)</label>
                <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price ? (int)$product->sale_price : '') }}" class="form-control rounded-pill @error('sale_price') is-invalid @enderror" placeholder="Để trống nếu không giảm">
                @error('sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Số lượng tồn kho <span class="text-danger">*</span></label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="form-control rounded-pill" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Link ảnh đại diện hoa (URL ảnh)</label>
                <div class="input-group">
                    <input type="url" name="thumbnail" id="thumbnail_input" value="{{ old('thumbnail', $product->thumbnail) }}" class="form-control rounded-start-pill" placeholder="https://images.unsplash.com/..." onchange="document.getElementById('img_preview').src = this.value">
                    <span class="input-group-text rounded-end-pill bg-light">
                        <i class="fa-regular fa-image"></i>
                    </span>
                </div>
                <div class="mt-2 d-flex align-items-center gap-3">
                    <img id="img_preview" src="{{ $product->primary_image }}" alt="{{ $product->name }}" class="rounded-3 border p-1 bg-white" style="width: 70px; height: 70px; object-fit: cover;">
                    <small class="text-muted">Ảnh xem trước hiện tại của mẫu hoa.</small>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Mô tả chi tiết & ý nghĩa hoa</label>
                <textarea name="description" rows="4" class="form-control rounded-4" placeholder="Mô tả ý nghĩa các loài hoa trong bó...">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="col-12">
                <div class="p-3 bg-light rounded-4 d-flex flex-wrap gap-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="featCheck" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="featCheck">
                            <i class="fa-solid fa-star text-warning me-1"></i> Đặt làm hoa nổi bật (Trang chủ)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="activeCheck" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="activeCheck">
                            <i class="fa-solid fa-circle-check text-success me-1"></i> Đang mở bán (Hiển thị trên website)
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Hủy</a>
                <button type="submit" class="btn btn-danger rounded-pill px-4">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Thay Đổi
                </button>
            </div>
        </div>
    </form>
</div>
@endsection