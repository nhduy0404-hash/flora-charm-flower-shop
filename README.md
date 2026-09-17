# 🌸 FLOWER SHOP - WEBSITE THƯƠNG MẠI ĐIỆN TỬ BÁN HOA TƯƠI
> **Đồ án môn học / Capstone Project - Nhóm 5 thành viên**  
> **Công nghệ lõi:** Laravel 10/11 (PHP 8.2), MySQL 8.0, Blade, Bootstrap 5 / Tailwind CSS, AJAX, Docker

---

## 📌 1. TỔNG QUAN DỰ ÁN
Hệ thống website bán hoa tươi trực tuyến với các tính năng chuyên biệt cho thị trường Việt Nam:
* Chọn ngày giao và khung giờ giao hoa linh hoạt.
* Viết lời nhắn in thiệp chúc mừng đính kèm đơn hàng.
* Tìm kiếm hoa gợi ý tức thì (Autocomplete) và Thêm vào giỏ hàng không tải lại trang (AJAX).
* Tự động sinh mã QR chuyển khoản ngân hàng chuẩn VietQR.
* Phân hệ quản trị (Admin Dashboard) quản lý hoa, đơn hàng và biểu đồ doanh thu.

---

## 🚀 2. HƯỚNG DẪN CÀI ĐẶT & KHỞI CHẠY (2 CHẾ ĐỘ)

### 🌿 CHẾ ĐỘ 1: Chạy bằng Laragon / XAMPP (Khuyến nghị cho cả nhóm)
1. **Clone repository về máy:**
   ```bash
   git clone <URL_REPO_GITHUB>
   cd flower_shop_project
   ```
2. **Cài đặt thư viện PHP:**
   ```bash
   composer install
   ```
3. **Cấu hình môi trường `.env`:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Mở file `.env`, kiểm tra tên database (mặc định: `flower_shop`) và tài khoản MySQL (`root`, password để trống nếu dùng Laragon).*

4. **Tạo CSDL & Nạp dữ liệu hoa mẫu:**
   ```bash
   php artisan migrate:fresh --seed
   ```
5. **Khởi động server phát triển:**
   ```bash
   php artisan serve
   ```
   *Truy cập website tại:* [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

### 🐳 CHẾ ĐỘ 2: Chạy bằng Docker Compose (Dành cho thành viên dùng Docker)
Chỉ cần cài sẵn Docker Desktop, chạy 1 lệnh duy nhất:
```bash
docker-compose up -d --build
```
* **Website:** [http://localhost:8000](http://localhost:8000)
* **phpMyAdmin (Quản lý DB):** [http://localhost:8080](http://localhost:8080) (User: `root`, Password: `root_password`)

---

## 🔐 3. TÀI KHOẢN ĐĂNG NHẬP MẪU ĐỂ DEMO
Sau khi chạy lệnh `php artisan db:seed`, hệ thống đã có sẵn 2 tài khoản:

| Vai trò | Email đăng nhập | Mật khẩu | Chức năng kiểm thử |
| :--- | :--- | :--- | :--- |
| **Quản trị viên (Admin)** | `admin@flowershop.vn` | `admin123` | Vào trang `/admin` quản lý hoa, duyệt đơn hàng |
| **Khách hàng (Customer)** | `khachhang@gmail.com` | `password123` | Đặt hoa, chọn thiệp, xem lịch sử đơn hàng |

---

## 📂 4. TÀI LIỆU QUẢN TRỊ & QUY CHẾ NHÓM
Nhóm lưu trữ đầy đủ tài liệu phục vụ báo cáo và vận hành tại:
* 📊 **[Báo Cáo Phân Tích Hệ Thống Thông Tin & Sơ Đồ ERD, DFD (docs/BAO_CAO_HE_THONG_THONG_TIN.md)](docs/BAO_CAO_HE_THONG_THONG_TIN.md)**: Chứa toàn bộ phân tích nghiệp vụ, ERD, DFD mức 0 & 1, BFD, kiến trúc Soft Deletes và Hybrid Image.
* 📜 **[Quy chế làm việc & Git Flow (CONTRIBUTING.md)](CONTRIBUTING.md)**: 6 luật bất di bất dịch, quy chuẩn đặt tên nhánh & commit.
* 📝 **[Nhật ký hoạt động (docs/WORK_LOG.md)](docs/WORK_LOG.md)**: Ghi chép tiến độ 4 tuần gắn liền với mã PR/Commit.
* 🔌 **[Đặc tả API nội bộ (docs/API_SPECIFICATION.md)](docs/API_SPECIFICATION.md)**: 5 RESTful API phục vụ tính năng mở rộng điểm 10.

---

## 👥 5. PHÂN CHIA VAI TRÒ 5 THÀNH VIÊN
* **Thành viên 1 (Leader):** Tech Lead, Architecture, Database Migrations, Auth & Middleware.
* **Thành viên 2:** Backend Catalog, Danh mục, Lọc hoa theo giá, Phân trang `->paginate(12)`.
* **Thành viên 3:** Backend Giỏ hàng, Checkout, VietQR, Email hóa đơn.
* **Thành viên 4:** Frontend Storefront, Master Layout Client, Chuẩn Responsive 100% Mobile.
* **Thành viên 5:** Frontend Admin Dashboard, Quản lý Đơn hàng, Viết Báo cáo Đồ án & Slide.
