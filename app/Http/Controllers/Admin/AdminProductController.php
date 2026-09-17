<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    /**
     * Danh sách sản phẩm hoa
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Form thêm mới hoa
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Lưu hoa mới vào CSDL
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'thumbnail' => 'nullable|url',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = Str::slug($request->name);
        $count = Product::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $slug,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock_quantity' => $request->stock_quantity,
            'thumbnail' => $request->thumbnail ?: 'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?w=800&q=80',
            'description' => $request->description,
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Đã thêm sản phẩm hoa mới thành công!');
    }

    /**
     * Form chỉnh sửa thông tin hoa
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật thông tin hoa vào CSDL
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'thumbnail' => 'nullable|url',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        // Cập nhật slug nếu đổi tên sản phẩm
        if ($product->name !== $request->name) {
            $slug = Str::slug($request->name);
            $count = Product::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $product->id)->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }
            $product->slug = $slug;
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $product->slug,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock_quantity' => $request->stock_quantity,
            'thumbnail' => $request->thumbnail ?: $product->thumbnail,
            'description' => $request->description,
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.products.index')->with('success', "Đã cập nhật mẫu hoa '{$product->name}' thành công!");
    }

    /**
     * Xóa hoa hoặc Ẩn nếu đã có đơn hàng
     */
    public function destroy($id)
    {
        $product = Product::withCount('orderItems')->findOrFail($id);

        // Bảo toàn dữ liệu: Nếu hoa đã có trong đơn hàng của khách, chuyển sang Ẩn bán
        if ($product->order_items_count > 0) {
            $product->update(['is_active' => false]);
            return redirect()->route('admin.products.index')->with('success', "Mẫu hoa '{$product->name}' đã có trong lịch sử đơn hàng, hệ thống đã tự động chuyển sang chế độ [Tạm Ngừng Bán] để bảo toàn dữ liệu!");
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm hoa thành công!');
    }
}
