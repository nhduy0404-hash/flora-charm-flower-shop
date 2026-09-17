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

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
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

            <!-- Cơ chế ảnh đại diện Hybrid (Tải tệp từ máy HOẶC Dán URL) -->
            @php
                $isLocalImage = $product->thumbnail && !str_starts_with($product->thumbnail, 'http://') && !str_starts_with($product->thumbnail, 'https://');
            @endphp
            <div class="col-12">
                <div class="card border rounded-4 p-3 bg-light-subtle">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0 text-dark">
                            <i class="fa-solid fa-image me-1 text-danger"></i> Cập nhật ảnh đại diện hoa (Cơ chế Hybrid)
                        </label>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="image_mode" id="mode_upload" value="upload" {{ $isLocalImage ? 'checked' : '' }} autocomplete="off" onchange="switchImageMode('upload')">
                            <label class="btn btn-outline-danger" for="mode_upload"><i class="fa-solid fa-upload me-1"></i> Tải từ máy</label>

                            <input type="radio" class="btn-check" name="image_mode" id="mode_url" value="url" {{ !$isLocalImage ? 'checked' : '' }} autocomplete="off" onchange="switchImageMode('url')">
                            <label class="btn btn-outline-danger" for="mode_url"><i class="fa-solid fa-link me-1"></i> Dán link URL</label>
                        </div>
                    </div>

                    <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <!-- Khung 1: Tải file mới từ máy tính -->
                            <div id="upload_box" style="{{ $isLocalImage ? '' : 'display: none;' }}">
                                <input type="file" name="image_file" id="image_file_input" class="form-control rounded-pill" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewLocalImage(this)">
                                <div class="form-text small text-muted">
                                    <i class="fa-solid fa-circle-info me-1"></i> Chọn file ảnh mới để thay thế. Hỗ trợ JPG, PNG, WEBP (Tối đa 2MB).
                                    @if($isLocalImage)
                                        <span class="badge bg-success-subtle text-success border ms-1">Đang lưu trong Storage</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Khung 2: Nhập URL bên ngoài -->
                            <div id="url_box" style="{{ !$isLocalImage ? '' : 'display: none;' }}">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fa-solid fa-globe text-muted"></i></span>
                                    <input type="text" name="thumbnail" id="thumbnail_url_input" value="{{ old('thumbnail', $product->thumbnail) }}" class="form-control" placeholder="https://images.unsplash.com/..." oninput="previewUrlImage(this.value)">
                                </div>
                                <div class="form-text small text-muted">
                                    <i class="fa-solid fa-circle-info me-1"></i> Nhập link ảnh mới từ Unsplash, Pexels hoặc CDN ngoài.
                                    @if(!$isLocalImage && $product->thumbnail)
                                        <span class="badge bg-info-subtle text-info border ms-1">Đang dùng Link CDN</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Khung xem trước ảnh (Live Preview) -->
                        <div class="col-md-4 text-center">
                            <div class="d-inline-block position-relative">
                                <img id="image_live_preview" src="{{ $product->primary_image }}" alt="{{ $product->name }}" class="rounded-4 border shadow-sm" style="width: 110px; height: 110px; object-fit: cover;">
                                <div class="small text-muted mt-1 fw-semibold">Ảnh hiển thị hiện tại</div>
                            </div>
                        </div>
                    </div>
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

@section('scripts')
<script>
    function switchImageMode(mode) {
        const uploadBox = document.getElementById('upload_box');
        const urlBox = document.getElementById('url_box');
        const fileInput = document.getElementById('image_file_input');
        const urlInput = document.getElementById('thumbnail_url_input');

        if (mode === 'upload') {
            uploadBox.style.display = 'block';
            urlBox.style.display = 'none';
            if (fileInput.files && fileInput.files[0]) {
                previewLocalImage(fileInput);
            }
        } else {
            uploadBox.style.display = 'none';
            urlBox.style.display = 'block';
            if (urlInput.value) {
                previewUrlImage(urlInput.value);
            }
        }
    }

    function previewLocalImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image_live_preview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewUrlImage(url) {
        if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
            document.getElementById('image_live_preview').src = url;
        }
    }
</script>
@endsection