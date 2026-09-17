<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderTrackingController extends Controller
{
    /**
     * Hiển thị trang tra cứu tiến độ đơn hàng
     */
    public function index(Request $request)
    {
        $order = null;
        if ($request->filled('order_number') && $request->filled('phone')) {
            $orderNumber = trim($request->input('order_number'));
            $phone = trim($request->input('phone'));

            $order = Order::with('orderItems')
                ->where('order_number', $orderNumber)
                ->where('receiver_phone', $phone)
                ->first();

            if (!$order) {
                session()->flash('error', 'Không tìm thấy đơn hàng phù hợp với thông tin đã nhập!');
            }
        }

        return view('orders.track', compact('order'));
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
