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
            $coupon = Coupon::where('code', $request->coupon_code)->first();
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
     * Trang thông báo đặt hàng thành công (kèm mã VietQR nếu chọn chuyển khoản)
     */
    public function success($orderNumber)
    {
        $order = Order::with('orderItems')->where('order_number', $orderNumber)->firstOrFail();

        // Tự động tạo mã VietQR nếu chọn thanh toán Chuyển khoản ngân hàng
        $vietQrUrl = null;
        if ($order->payment_method === 'bank_transfer') {
            $bankId = 'MB'; // Ngân hàng Quân Đội MB Bank
            $accountNo = '0988888888';
            $accountName = urlencode('SHOP HOA TUOI');
            $amount = (int) $order->total_amount;
            $addInfo = urlencode($order->order_number);
            $vietQrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-compact2.png?amount={$amount}&addInfo={$addInfo}&accountName={$accountName}";
        }

        return view('checkout.success', compact('order', 'vietQrUrl'));
    }
}
