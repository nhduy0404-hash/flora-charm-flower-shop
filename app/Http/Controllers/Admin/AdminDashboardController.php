<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;

class AdminDashboardController extends Controller
{
    /**
     * Hiển thị bảng điều khiển thống kê tổng quan
     */
    public function index()
    {
        // Doanh thu thực tế là tổng tiền các đơn đã thanh toán (Paid)
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        // Tiền hàng chờ thu (Chưa thanh toán và không bị hủy)
        $unpaidRevenue = Order::where('payment_status', 'unpaid')->where('order_status', '!=', 'cancelled')->sum('total_amount');

        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();

        // 10 đơn hàng mới nhất
        $recentOrders = Order::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'unpaidRevenue',
            'totalOrders',
            'pendingOrders',
            'totalProducts',
            'totalCustomers',
            'recentOrders'
        ));
    }
}
