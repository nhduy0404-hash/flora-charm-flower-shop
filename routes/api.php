<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Flower Shop (Selective Internal APIs)
|--------------------------------------------------------------------------
*/

// 1. API Tìm kiếm gợi ý hoa tức thì (Autocomplete)
Route::get('/flowers/search', function (Request $request) {
    $query = $request->query('q', '');
    return response()->json([
        'success' => true,
        'query' => $query,
        'data' => []
    ]);
});

// 2. API Thêm vào giỏ hàng không reload trang (AJAX Add to Cart)
Route::post('/cart/add', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
        'cart_count' => 1
    ]);
});

// 3. API Cập nhật số lượng giỏ hàng
Route::patch('/cart/items/{productId}', function ($productId, Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Đã cập nhật số lượng!',
        'product_id' => $productId
    ]);
});

// 4. API Tra cứu tiến độ đơn hàng
Route::get('/orders/track', function (Request $request) {
    $orderNumber = $request->query('order_number');
    return response()->json([
        'success' => true,
        'order_number' => $orderNumber,
        'status' => 'pending'
    ]);
});

// 5. API Sinh mã VietQR chuyển khoản
Route::get('/orders/{orderNumber}/vietqr', function ($orderNumber) {
    return response()->json([
        'success' => true,
        'order_number' => $orderNumber,
        'qr_url' => "https://img.vietqr.io/image/MB-0987654321-compact2.png?amount=500000&addInfo={$orderNumber}&accountName=FLOWER%20SHOP"
    ]);
});
