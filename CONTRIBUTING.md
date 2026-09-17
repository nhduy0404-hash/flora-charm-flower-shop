# Quy Chế Đóng Góp & Quy Trình Git Nhóm (CONTRIBUTING.md)
## Dự án: Website Bán Hoa Tươi (Flower Shop) - Laravel

Chào mừng bạn đến với dự án! Để đảm bảo mã nguồn luôn ổn định, không bị xung đột (conflict) và đạt điểm cao nhất khi bảo vệ đồ án, tất cả 5 thành viên bắt buộc phải tuân thủ nghiêm ngặt các quy tắc dưới đây.

---

## 1. 6 Điều Luật Bất Di Bất Dịch (Golden Rules)

1. **Tuyệt đối KHÔNG code hoặc push trực tiếp lên `main` và `develop`.**
2. **Một tính năng = Một nhánh riêng = Một Pull Request (PR).**
3. **Trước khi bắt đầu code mỗi ngày:** Kéo code mới nhất từ `develop` về máy cục bộ.
4. **Mỗi Pull Request phải có ít nhất 1 người duyệt (Approve)** và chạy thử không phát sinh lỗi trước khi hợp nhất (merge).
5. **Cấm tuyệt đối lệnh `git push --force`.**
6. **Không commit file nhạy cảm và thư viện:** `.env`, `/vendor`, `/node_modules`, `.DS_Store`.

---

## 2. Mô Hình Phân Nhánh Git (Branching Strategy)

```text
main           ─── Chỉ chứa bản chạy hoàn hảo để nộp bài/demo (gắn tag v1.0.0)
  ▲
  │ (Merge sau khi test kỹ)
develop        ─── Nơi tích hợp và chạy thử chung của cả 5 thành viên
  ▲
  ├── feature/auth-user          (Thành viên 1 - Leader)
  ├── feature/flower-catalog     (Thành viên 2)
  ├── feature/cart-checkout      (Thành viên 3)
  ├── feature/client-ui          (Thành viên 4)
  ├── feature/admin-dashboard    (Thành viên 5)
  │
  └── fix/<tên-lỗi>             (Sửa lỗi phát sinh)
```

---

## 3. Quy Ước Đặt Tên & Commit Chuẩn (Conventional Commits)

### 3.1. Đặt tên nhánh (Branch Naming)
* Tính năng mới: `feature/<tên-ngắn-gọn>` (ví dụ: `feature/cart-session`, `feature/vietqr-payment`)
* Sửa lỗi: `fix/<tên-lỗi>` (ví dụ: `fix/card-message-overflow`, `fix/login-validation`)
* Giao diện: `style/<tên-trang>` (ví dụ: `style/mobile-navbar`, `style/checkout-form`)
* Tài liệu: `docs/<nội-dung>` (ví dụ: `docs/api-guide`, `docs/work-log`)

### 3.2. Viết Commit Message
Cú pháp: `<loại>: <mô tả ngắn gọn bằng tiếng Anh>`
* `feat:` Tính năng mới (ví dụ: `feat: add flower search with price range filter`)
* `fix:` Sửa lỗi (ví dụ: `fix: correct subtotal calculation on coupon apply`)
* `style:` Sửa layout/CSS (ví dụ: `style: responsive flower detail page on mobile`)
* `refactor:` Tối ưu code không đổi logic (ví dụ: `refactor: extract order calculation to service`)
* `test:` Thêm test case (ví dụ: `test: add unit test for order status transition`)
* `docs:` Sửa tài liệu (ví dụ: `docs: update weekly work log for sprint 1`)

---

## 4. Quy Trình Làm Việc Hàng Ngày Của Mỗi Thành Viên

```bash
# Bước 1: Kéo code mới nhất từ nhánh develop
git checkout develop
git pull origin develop

# Bước 2: Tạo nhánh riêng cho tính năng của bạn
git checkout -b feature/<tên-tính-năng>

# Bước 3: Code và test chạy ngon lành trên máy bạn
# ... code code code ...

# Bước 4: Lưu thay đổi với commit rõ ràng
git add .
git commit -m "feat: implement flower category list"

# Bước 5: Đẩy nhánh lên GitHub
git push origin feature/<tên-tính-năng>

# Bước 6: Lên GitHub tạo Pull Request vào nhánh 'develop' và nhờ bạn khác review
```

---

## 5. Quy Tắc Xử Lý Database Migration trong Laravel

* **Tuần 1:** Chạy toàn bộ 7 file migration có sẵn trong `database/migrations/`:
  ```bash
  php artisan migrate:fresh --seed
  ```
* **Khi cần thêm cột mới ở các tuần sau:**
  * **TUYỆT ĐỐI KHÔNG** sửa trực tiếp vào file migration cũ đã merge vào `develop`.
  * Tạo migration mới dạng bổ sung:
    ```bash
    php artisan make:migration add_avatar_to_users_table
    ```
  * Báo ngay lên nhóm Zalo/Discord để các thành viên khác pull về và chạy `php artisan migrate`.
