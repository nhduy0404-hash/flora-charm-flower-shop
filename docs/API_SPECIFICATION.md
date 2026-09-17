# ĐẶC TẢ RESTFUL API NỘI BỘ (INTERNAL API SPECIFICATION)
## Dự án: Website Bán Hoa Tươi (Flower Shop) - Laravel

Tài liệu này đặc tả các API phục vụ trải nghiệm người dùng hiện đại (không reload trang) và tính năng mở rộng đạt điểm 10/10.

---

### 1. API Tìm Kiếm Gợi Ý Hoa Tức Thì (Search Autocomplete)
* **Endpoint:** `GET /api/flowers/search`
* **Mô tả:** Trả về danh sách gợi ý tối đa 5 hoa phù hợp khi người dùng gõ phím vào ô tìm kiếm.
* **Query Parameters:**
  * `q` (string, required): Từ khóa tìm kiếm (ví dụ: `hong`, `lan`, `baby`).
* **Mẫu Response thành công (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Bó Hoa Hồng Đỏ Lãng Mạn",
      "slug": "bo-hoa-hong-do-lang-man",
      "price": 450000,
      "sale_price": 399000,
      "thumbnail": "https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11",
      "category_name": "Hoa Tình Yêu"
    }
  ]
}
```

---

### 2. API Thêm Vào Giỏ Hàng Không Reload (AJAX Add to Cart)
* **Endpoint:** `POST /api/cart/add`
* **Mô tả:** Thêm một bó hoa vào giỏ hàng (lưu Session), trả về tổng số lượng mới để cập nhật icon giỏ hàng.
* **Request Body:**
```json
{
  "product_id": 1,
  "quantity": 1
}
```
* **Mẫu Response thành công (200 OK):**
```json
{
  "success": true,
  "message": "Đã thêm Bó Hoa Hồng Đỏ Lãng Mạn vào giỏ hàng!",
  "cart_count": 3,
  "subtotal": 1250000
}
```
* **Mẫu Response lỗi hết hàng (422 Unprocessable Entity):**
```json
{
  "success": false,
  "message": "Sản phẩm này hiện chỉ còn 2 bó trong kho!"
}
```

---

### 3. API Cập Nhật Số Lượng Giỏ Hàng (AJAX Update Cart)
* **Endpoint:** `PATCH /api/cart/items/{product_id}`
* **Mô tả:** Tăng hoặc giảm số lượng của một mục trong giỏ hàng.
* **Request Body:**
```json
{
  "quantity": 2
}
```
* **Mẫu Response thành công (200 OK):**
```json
{
  "success": true,
  "item_total": 798000,
  "cart_subtotal": 1648000,
  "cart_count": 4
}
```

---

### 4. API Tra Cứu Tiến Độ Đơn Hàng (Order Tracking Timeline)
* **Endpoint:** `GET /api/orders/track`
* **Mô tả:** Khách vãng lai tra cứu đơn hàng bằng Mã đơn hàng + Số điện thoại.
* **Query Parameters:**
  * `order_number` (string, required): ví dụ `FLW-202609-001`
  * `phone` (string, required): ví dụ `0901234567`
* **Mẫu Response thành công (200 OK):**
```json
{
  "success": true,
  "data": {
    "order_number": "FLW-202609-001",
    "receiver_name": "Trần Thị Mai",
    "receiver_phone": "0901234567",
    "delivery_date": "2026-09-20",
    "delivery_time_slot": "09:00 - 11:00",
    "card_message": "Chúc mừng sinh nhật em yêu!",
    "total_amount": 450000,
    "payment_method": "VietQR",
    "payment_status": "paid",
    "order_status": "delivering",
    "status_steps": [
      { "step": "pending", "label": "Chờ duyệt", "completed": true, "time": "2026-09-17 10:00" },
      { "step": "processing", "label": "Đang cắm hoa", "completed": true, "time": "2026-09-17 10:30" },
      { "step": "delivering", "label": "Đang giao hoa", "completed": true, "time": "2026-09-17 11:00" },
      { "step": "completed", "label": "Đã giao thành công", "completed": false, "time": null }
    ]
  }
}
```

---

### 5. API Sinh Mã Thanh Toán VietQR Tự Động
* **Endpoint:** `GET /api/orders/{order_number}/vietqr`
* **Mô tả:** Trả về đường dẫn ảnh mã QR ngân hàng chuẩn VietQR kèm số tiền và nội dung chuyển khoản.
* **Mẫu Response thành công (200 OK):**
```json
{
  "success": true,
  "qr_url": "https://img.vietqr.io/image/MB-0987654321-compact2.png?amount=450000&addInfo=FLW-202609-001&accountName=FLOWER%20SHOP",
  "bank_name": "MB Bank",
  "account_number": "0987654321",
  "account_name": "FLOWER SHOP",
  "amount": 450000,
  "transfer_content": "FLW-202609-001"
}
```
