<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class AdminOrderController extends Controller
{
    /**
     * Danh sách đơn hàng phía quản trị kèm tìm kiếm & bộ lọc
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product'])->latest();

        // 1. Tìm kiếm theo Mã đơn hàng, Tên người nhận, SĐT
        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));
            $query->where(function ($q) use ($keyword) {
                $q->where('order_number', 'LIKE', "%{$keyword}%")
                  ->orWhere('receiver_name', 'LIKE', "%{$keyword}%")
                  ->orWhere('receiver_phone', 'LIKE', "%{$keyword}%");
            });
        }

        // 2. Lọc theo trạng thái tiến độ đơn
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // 3. Lọc theo trạng thái thanh toán
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Xem chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.product', 'coupon'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cập nhật trạng thái đơn hàng & hoàn trả tồn kho nếu hủy đơn
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,delivering,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid',
        ]);

        $order = Order::with('orderItems')->findOrFail($id);
        $oldStatus = $order->order_status;
        $newStatus = $request->order_status;

        // Logic Hoàn Kho: Nếu hủy đơn, tự động cộng lại tồn kho các mẫu hoa
        if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
            foreach ($order->orderItems as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
                }
            }
        }
        // Nếu khôi phục từ Đã hủy sang trạng thái khác, trừ lại tồn kho
        elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            foreach ($order->orderItems as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)->decrement('stock_quantity', $item->quantity);
                }
            }
        }

        $order->update([
            'order_status' => $newStatus,
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', "Đã cập nhật trạng thái đơn hàng #{$order->order_number}!");
    }

    /**
     * Nút bấm nhanh 1-click xác nhận đã thu tiền đơn hàng
     */
    public function quickMarkPaid($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['payment_status' => 'paid']);

        return back()->with('success', "Đã xác nhận thu tiền thành công cho đơn hàng #{$order->order_number}!");
    }
}
