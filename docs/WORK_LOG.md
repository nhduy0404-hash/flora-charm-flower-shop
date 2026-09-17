# NHẬT KÝ HOẠT ĐỘNG DỰ ÁN (PROJECT WORK LOG)
## Dự án: Website Bán Hoa Tươi (Flower Shop) - Laravel
**Quy mô nhóm:** 5 thành viên  
*Lưu ý: Bảng nhật ký này là bằng chứng thép để bảo vệ điểm đóng góp cá nhân trước hội đồng chấm thi.*

---

## 📅 TUẦN 1: Khởi Tạo Kiến Trúc, Database & Giao Diện Cơ Bản

| Thành viên | Vai trò | Nhiệm vụ đảm nhận | Kết quả hoàn thành | Mã PR / Commit | Khó khăn & Cách giải quyết |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Thành viên 1 (Leader)** | Tech Lead / Backend | Khởi tạo Laravel, viết 7 Migrations & Seeders, cấu hình Docker | Hoàn tất 7 bảng DB, seeder dữ liệu hoa mẫu thành công | PR #1, PR #2 | Xung đột thứ tự foreign key giữa orders và users -> Đổi thứ tự migration |
| **Thành viên 2** | Backend Catalog | Viết Model, Controller cho Category & Product | API danh mục và trang danh sách hoa có phân trang | PR #3 | Phân trang bị mất query params khi lọc -> Dùng `withQueryString()` |
| **Thành viên 3** | Backend Cart & Order | Xây dựng logic giỏ hàng lưu Session | Thêm/sửa/xóa hoa trong giỏ hàng hoạt động tốt | PR #4 | Xử lý số lượng tồn kho khi hoa hết hàng -> Viết thêm validation check stock |
| **Thành viên 4** | Frontend Client | Dựng Master Layout Blade phía Khách (Header, Footer, Navbar) | Giao diện chuẩn Responsive 100% Mobile & Desktop | PR #5 | Menu mobile bị che khuất -> Điều chỉnh z-index và breakpoint Bootstrap |
| **Thành viên 5** | Admin UI & QA | Dựng Master Layout Admin Dashboard, quản lý tài liệu | Hoàn thành khung Sidebar Admin, tạo file SRS & Quy chế | Commit `a1b2c3` | Thư viện Chart.js chưa ăn dữ liệu động -> Tạm thời mock dữ liệu mẫu |

---

## 📅 TUẦN 2: Phát Triển Tính Năng Lõi (Core Features)

| Thành viên | Vai trò | Nhiệm vụ đảm nhận | Kết quả hoàn thành | Mã PR / Commit | Khó khăn & Cách giải quyết |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Thành viên 1** | Tech Lead | Authentication (Đăng ký, Đăng nhập) & Phân quyền Middleware | Chặn khách không vào được `/admin`, phân quyền xong | PR #... | |
| **Thành viên 2** | Backend | Bộ lọc hoa nâng cao theo khoảng giá, danh mục, sắp xếp | Trang danh sách hoa lọc mượt mà | PR #... | |
| **Thành viên 3** | Backend | Trang Checkout: Lưu thông tin người nhận, ngày giờ giao hoa | Đơn hàng lưu đầy đủ vào bảng `orders` | PR #... | |
| **Thành viên 4** | Frontend | Trang chi tiết hoa (Gallery ảnh, mô tả, đánh giá) & Giỏ hàng | Khách xem ảnh nét, tương tác giỏ hàng đẹp | PR #... | |
| **Thành viên 5** | Admin UI | Trang CRUD Quản lý Hoa và Danh mục trong Admin | Admin thêm/sửa/xóa hoa có upload ảnh ngon lành | PR #... | |

---

## 📅 TUẦN 3: Tích Hợp AJAX, Thanh Toán & Quản Lý Đơn Hàng

| Thành viên | Vai trò | Nhiệm vụ đảm nhận | Kết quả hoàn thành | Mã PR / Commit | Khó khăn & Cách giải quyết |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Thành viên 1** | Tech Lead | Tích hợp gửi email hóa đơn tự động khi đặt hàng | Email gửi thông tin đơn hàng và thiệp chúc mừng | PR #... | |
| **Thành viên 2** | Backend | Tìm kiếm hoa tức thì (Search autocomplete AJAX) | Khách gõ tên hoa hiện gợi ý ngay lập tức | PR #... | |
| **Thành viên 3** | Backend | Thêm giỏ hàng bằng AJAX không reload + Tạo mã VietQR | Icon giỏ hàng nhảy số (+1), hiện mã QR ngân hàng | PR #... | |
| **Thành viên 4** | Frontend | Hoàn thiện trang Checkout form có chọn thiệp và ngày giao | Form mượt mà, kiểm tra số điện thoại hợp lệ | PR #... | |
| **Thành viên 5** | Admin UI | Quản lý Đơn hàng & Chuyển trạng thái đơn trong Admin | Admin duyệt đơn, cập nhật trạng thái giao hoa | PR #... | |

---

## 📅 TUẦN 4: Kiểm Thử, Đóng Gói, Báo Cáo & Nghiệm Thu

| Thành viên | Vai trò | Nhiệm vụ đảm nhận | Kết quả hoàn thành | Mã PR / Commit | Khó khăn & Cách giải quyết |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Cả nhóm** | Toàn đội | Kiểm thử tổng thể (Integration Test), sửa lỗi tồn đọng | Hệ thống chạy trơn tru, không có lỗi nghiêm trọng | Release `v1.0.0` | |
| **Thành viên 5** | Báo cáo | Chụp ảnh màn hình, hoàn thiện Báo cáo Word & Slide | Nộp bản báo cáo đầy đủ Use Case, ERD, Nhật ký | Báo cáo.docx | |
