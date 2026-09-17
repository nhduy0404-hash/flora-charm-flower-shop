<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class AdminCouponController extends Controller
{
    /**
     * Danh sách mã khuyến mãi (Coupons)
     */
    public function index()
    {
        $coupons = Coupon::withCount('orders')->latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Form tạo mã giảm giá mới
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Lưu mã giảm giá mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date|after:today',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        Coupon::create([
            'code' => strtoupper(trim($request->code)),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_amount' => $request->min_order_amount ?: 0,
            'expires_at' => $request->expires_at,
            'usage_limit' => $request->usage_limit,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Đã tạo mã giảm giá mới thành công!');
    }

    /**
     * Form sửa mã giảm giá
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Cập nhật mã giảm giá
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $coupon->update([
            'code' => strtoupper(trim($request->code)),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_order_amount' => $request->min_order_amount ?: 0,
            'expires_at' => $request->expires_at,
            'usage_limit' => $request->usage_limit,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.coupons.index')->with('success', "Đã cập nhật mã '{$coupon->code}' thành công!");
    }

    /**
     * Xóa mã giảm giá
     */
    public function destroy($id)
    {
        $coupon = Coupon::withCount('orders')->findOrFail($id);

        if ($coupon->orders_count > 0) {
            $coupon->update(['is_active' => false]);
            return redirect()->route('admin.coupons.index')->with('success', "Mã '{$coupon->code}' đã có đơn hàng sử dụng, hệ thống chuyển sang trạng thái Vô hiệu hóa (Deactivated) để bảo toàn dữ liệu!");
        }

        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Đã xóa mã voucher!');
    }
}