<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderTrackingController extends Controller
{
    /**
     * Hiển thị trang tra cứu tiến độ đơn hàng
     */
    public function index(Request $request)
    {
        $order = null;
        $multipleOrders = null;
        $userRecentOrders = null;

        if ($request->filled('order_number')) {
            $orderNumber = trim($request->input('order_number'));
            $query = Order::with(['orderItems.product'])->where('order_number', $orderNumber);

            if ($request->filled('phone')) {
                $query->where('receiver_phone', trim($request->input('phone')));
            }

            $order = $query->first();

            if (!$order) {
                session()->flash('error', "Không tìm thấy đơn hàng có mã '{$orderNumber}'!");
            }
        } elseif ($request->filled('phone')) {
            $phone = trim($request->input('phone'));
            $multipleOrders = Order::with(['orderItems.product'])
                ->where('receiver_phone', $phone)
                ->latest()
                ->get();

            if ($multipleOrders->isEmpty()) {
                session()->flash('error', "Không tìm thấy đơn hàng nào gắn với số điện thoại '{$phone}'!");
            } elseif ($multipleOrders->count() === 1) {
                $order = $multipleOrders->first();
                $multipleOrders = null;
            }
        }

        // Nếu người dùng đã đăng nhập và chưa tìm kiếm, gợi ý các đơn hàng gần nhất của họ
        if (!$order && !$multipleOrders && Auth::check()) {
            $userRecentOrders = Order::with(['orderItems.product'])
                ->where('user_id', Auth::id())
                ->latest()
                ->take(5)
                ->get();
        }

        return view('orders.track', compact('order', 'multipleOrders', 'userRecentOrders'));
    }

    /**
     * Danh sách lịch sử mua hàng của khách hàng (Đã đăng nhập)
     */
    public function history()
    {
        $orders = Order::with(['orderItems.product'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.history', compact('orders'));
    }

    /**
     * API tra cứu tiến độ đơn hàng (JSON)
     */
    public function trackApi(Request $request)
    {
        $orderNumber = $request->query('order_number');
        $phone = $request->query('phone');

        $order = Order::with('orderItems')
            ->where('order_number', $orderNumber)
            ->where('receiver_phone', $phone)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng!'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order_number' => $order->order_number,
                'receiver_name' => $order->receiver_name,
                'order_status' => $order->order_status,
                'delivery_date' => $order->delivery_date->format('d/m/Y'),
                'delivery_time_slot' => $order->delivery_time_slot,
                'total_amount' => $order->total_amount,
                'items_count' => $order->orderItems->count()
            ]
        ]);
    }
}
