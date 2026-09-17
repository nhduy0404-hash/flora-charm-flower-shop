<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang đặt hàng & thanh toán
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống! Vui lòng chọn hoa trước khi đặt.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $user = Auth::user();
        return view('checkout.index', compact('cart', 'subtotal', 'user'));
    }

    /**
     * Xử lý đặt hàng
     */
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|regex:/^[0-9]{10,11}$/',
            'shipping_address' => 'required|string|max:500',
            'delivery_date' => 'required|date|after_or_equal:today',
            'delivery_time_slot' => 'required|string',
            'card_message' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cod,bank_transfer',
            'coupon_code' => 'nullable|string',
            'note' => 'nullable|string|max:500',
        ], [
            'receiver_name.required' => 'Vui lòng nhập họ tên người nhận hoa!',
            'receiver_phone.required' => 'Vui lòng nhập số điện thoại người nhận!',
            'receiver_phone.regex' => 'Số điện thoại không hợp lệ (cần 10-11 số)!',
            'shipping_address.required' => 'Vui lòng nhập địa chỉ giao hoa!',
            'delivery_date.required' => 'Vui lòng chọn ngày giao hoa!',
            'delivery_date.after_or_equal' => 'Ngày giao hoa phải từ hôm nay trở đi!',
            'delivery_time_slot.required' => 'Vui lòng chọn khung giờ giao hoa!',
        ]);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Kiểm tra mã giảm giá nếu có
        $discountAmount = 0;
        $couponId = null;
        if ($request->filled('coupon_code')) {
            $couponCode = strtoupper(trim($request->coupon_code));
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValidForAmount($subtotal)) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
                $couponId = $coupon->id;
                $coupon->increment('used_count');
            }
        }

        $totalAmount = max(0, $subtotal - $discountAmount);

        // Sử dụng Transaction để đảm bảo tính toàn vẹn dữ liệu
        $order = DB::transaction(function () use ($request, $cart, $subtotal, $discountAmount, $totalAmount, $couponId) {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'coupon_id' => $couponId,
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'shipping_address' => $request->shipping_address,
                'delivery_date' => $request->delivery_date,
                'delivery_time_slot' => $request->delivery_time_slot,
                'card_message' => $request->card_message,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'note' => $request->note,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);

                // Trừ số lượng tồn kho
                Product::where('id', $item['id'])->decrement('stock_quantity', $item['quantity']);
            }

            return $order;
        });

        // Xóa giỏ hàng sau khi đặt thành công
        session()->forget('cart');

        return redirect()->route('checkout.success', ['orderNumber' => $order->order_number]);
    }

    /**
     * AJAX Kiểm tra mã giảm giá và tính toán số tiền giảm tức thì
     */
    public function checkCoupon(Request $request)
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $code = strtoupper(trim($request->input('code', '')));
        if (empty($code)) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập mã voucher!'
            ], 422);
        }

        $coupon = Coupon::where('code', $code)->first();
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => "Mã giảm giá '{$code}' không tồn tại trên hệ thống!"
            ], 404);
        }

        if (!$coupon->isValidForAmount($subtotal)) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá này đã hết hạn hoặc chưa đạt giá trị đơn tối thiểu!'
            ], 422);
        }

        $discount = $coupon->calculateDiscount($subtotal);
        $newTotal = max(0, $subtotal - $discount);

        return response()->json([
            'success' => true,
            'code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount_percent' => $coupon->discount_type === 'percentage' ? (float)$coupon->discount_value : null,
            'discount_amount' => $discount,
            'discount_formatted' => number_format($discount) . ' đ',
            'new_total' => $newTotal,
            'new_total_formatted' => number_format($newTotal) . ' đ',
            'message' => "Áp dụng thành công voucher {$coupon->code} (-" . ($coupon->discount_type === 'percentage' ? (int)$coupon->discount_value . '%' : number_format($discount) . ' đ') . ")!"
        ]);
    }

    /**
     * Trang thông báo đặt hàng thành công (kèm mã VietQR nếu chọn chuyển khoản)
     */
    public function success($orderNumber)
    {
        $order = Order::with('orderItems')->where('order_number', $orderNumber)->firstOrFail();

        // Tự động tạo mã VietQR nếu chọn thanh toán Chuyển khoản ngân hàng (MB Bank: 0369710409)
        $vietQrUrl = null;
        $bankAccountNo = env('VIETQR_ACCOUNT_NO', '0369710409');
        $bankId = env('VIETQR_BANK_ID', 'MB');
        $bankAccountName = env('VIETQR_ACCOUNT_NAME', 'FLORA CHARM');

        if ($order->payment_method === 'bank_transfer') {
            $accountNameEncoded = urlencode($bankAccountName);
            $amount = (int) $order->total_amount;
            $addInfo = urlencode($order->order_number);
            $vietQrUrl = "https://img.vietqr.io/image/{$bankId}-{$bankAccountNo}-compact2.png?amount={$amount}&addInfo={$addInfo}&accountName={$accountNameEncoded}";
        }

        return view('checkout.success', compact('order', 'vietQrUrl', 'bankAccountNo', 'bankId', 'bankAccountName'));
    }
}
