@extends('layouts.admin')

@section('title', 'Thêm Mẫu Hoa Mới - FloraCharm Admin')
@section('page_title', 'Thêm Mẫu Hoa Tươi Mới')

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white p-4 mx-auto" style="max-width: 800px;">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
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

            <!-- Cơ chế ảnh đại diện Hybrid (Tải tệp từ máy HOẶC Dán URL) -->
            <div class="col-12">
                <div class="card border rounded-4 p-3 bg-light-subtle">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0 text-dark">
                            <i class="fa-solid fa-image me-1 text-danger"></i> Ảnh đại diện hoa tươi (Cơ chế Hybrid)
                        </label>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="image_mode" id="mode_upload" value="upload" checked autocomplete="off" onchange="switchImageMode('upload')">
                            <label class="btn btn-outline-danger" for="mode_upload"><i class="fa-solid fa-upload me-1"></i> Tải từ máy</label>

                            <input type="radio" class="btn-check" name="image_mode" id="mode_url" value="url" autocomplete="off" onchange="switchImageMode('url')">
                            <label class="btn btn-outline-danger" for="mode_url"><i class="fa-solid fa-link me-1"></i> Dán link URL</label>
                        </div>
                    </div>

                    <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <!-- Khung 1: Tải file từ máy tính -->
                            <div id="upload_box">
                                <input type="file" name="image_file" id="image_file_input" class="form-control rounded-pill" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewLocalImage(this)">
                                <div class="form-text small text-muted"><i class="fa-solid fa-circle-info me-1"></i> Hỗ trợ định dạng JPG, PNG, WEBP (Tối đa 2MB). Ảnh lưu trữ an toàn trong máy chủ.</div>
                            </div>

                            <!-- Khung 2: Nhập URL bên ngoài -->
                            <div id="url_box" style="display: none;">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fa-solid fa-globe text-muted"></i></span>
                                    <input type="text" name="thumbnail" id="thumbnail_url_input" value="{{ old('thumbnail') }}" class="form-control" placeholder="https://images.unsplash.com/..." oninput="previewUrlImage(this.value)">
                                </div>
                                <div class="form-text small text-muted"><i class="fa-solid fa-circle-info me-1"></i> Dán trực tiếp đường link ảnh từ Unsplash, Pexels hoặc CDN.</div>
                            </div>
                        </div>

                        <!-- Khung xem trước ảnh (Live Preview) -->
                        <div class="col-md-4 text-center">
                            <div class="d-inline-block position-relative">
                                <img id="image_live_preview" src="https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?w=800&q=80" alt="Xem trước ảnh" class="rounded-4 border shadow-sm" style="width: 110px; height: 110px; object-fit: cover;">
                                <div class="small text-muted mt-1 fw-semibold">Xem trước (Preview)</div>
                            </div>
                        </div>
                    </div>
                </div>
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
