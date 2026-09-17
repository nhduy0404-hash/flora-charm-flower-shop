<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Xem trang giỏ hàng
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'subtotal'));
    }

    /**
     * Thêm hoa vào giỏ hàng (Hỗ trợ cả Form truyền thống và AJAX)
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = (int) $request->input('quantity', 1);

        if ($product->stock_quantity < $quantity) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng yêu cầu vượt quá số lượng hoa có trong kho!'
                ], 422);
            }
            return back()->with('error', 'Số lượng trong kho không đủ!');
        }

        $cart = session()->get('cart', []);
        $price = $product->sale_price && $product->sale_price > 0 ? (float) $product->sale_price : (float) $product->price;

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $price,
                'thumbnail' => $product->primary_image,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        $totalCount = array_sum(array_column($cart, 'quantity'));
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Đã thêm \"{$product->name}\" vào giỏ hàng!",
                'cart_count' => $totalCount,
                'subtotal' => $subtotal
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm hoa vào giỏ hàng thành công!');
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function update(Request $request, $productId)
    {
        $quantity = (int) $request->input('quantity', 1);
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }
            session()->put('cart', $cart);
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart_count' => array_sum(array_column($cart, 'quantity')),
                'item_total' => isset($cart[$productId]) ? $cart[$productId]['price'] * $cart[$productId]['quantity'] : 0,
                'subtotal' => $subtotal,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã cập nhật giỏ hàng!');
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function remove($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }
}
