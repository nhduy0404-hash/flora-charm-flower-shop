<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    /**
     * Danh sách sản phẩm hoa kèm tìm kiếm & lọc thùng rác
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->get('status') === 'trashed') {
            $query->onlyTrashed();
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('keyword')) {
            $kw = trim($request->keyword);
            $query->where('name', 'LIKE', "%{$kw}%");
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = Category::all();
        $trashedCount = Product::onlyTrashed()->count();

        return view('admin.products.index', compact('products', 'categories', 'trashedCount'));
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
     * Lưu hoa mới vào CSDL (Hỗ trợ Hybrid Upload: Tải file từ máy HOẶC Dán link URL)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'thumbnail' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = Str::slug($request->name);
        $count = Product::withTrashed()->where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        // Xử lý cơ chế ảnh Hybrid (Ưu tiên File upload, nếu không thì lấy link URL)
        $thumbnailPath = 'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?w=800&q=80';
        if ($request->hasFile('image_file')) {
            $thumbnailPath = $request->file('image_file')->store('products', 'public');
        } elseif ($request->filled('thumbnail')) {
            $thumbnailPath = trim($request->thumbnail);
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $slug,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock_quantity' => $request->stock_quantity,
            'thumbnail' => $thumbnailPath,
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
        $product = Product::withTrashed()->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Cập nhật thông tin hoa vào CSDL (Hỗ trợ Hybrid Image update)
     */
    public function update(Request $request, $id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'thumbnail' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        // Cập nhật slug nếu đổi tên sản phẩm
        if ($product->name !== $request->name) {
            $slug = Str::slug($request->name);
            $count = Product::withTrashed()->where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $product->id)->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }
            $product->slug = $slug;
        }

        // Xử lý cập nhật ảnh Hybrid
        $thumbnailPath = $product->thumbnail;
        if ($request->hasFile('image_file')) {
            // Xóa file ảnh cũ nếu trước đó là file cục bộ trong storage
            if ($product->thumbnail && !str_starts_with($product->thumbnail, 'http://') && !str_starts_with($product->thumbnail, 'https://')) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $thumbnailPath = $request->file('image_file')->store('products', 'public');
        } elseif ($request->filled('thumbnail') && $request->thumbnail !== $product->thumbnail) {
            // Nếu người dùng đổi sang link URL mới, xóa file cũ nếu trước đó là file cục bộ
            if ($product->thumbnail && !str_starts_with($product->thumbnail, 'http://') && !str_starts_with($product->thumbnail, 'https://')) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $thumbnailPath = trim($request->thumbnail);
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $product->slug,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock_quantity' => $request->stock_quantity,
            'thumbnail' => $thumbnailPath,
            'description' => $request->description,
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.products.index')->with('success', "Đã cập nhật mẫu hoa '{$product->name}' thành công!");
    }

    /**
     * Xóa mềm hoa (Đưa vào thùng rác SoftDeletes - bảo toàn lịch sử đơn hàng)
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', "Đã chuyển mẫu hoa '{$product->name}' vào thùng rác (Xóa mềm - Lịch sử đơn hàng vẫn bảo toàn nguyên vẹn)!");
    }

    /**
     * Khôi phục hoa từ thùng rác
     */
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('admin.products.index', ['status' => 'trashed'])->with('success', "Đã khôi phục mẫu hoa '{$product->name}' trở lại danh mục kinh doanh!");
    }

    /**
     * Xóa vĩnh viễn hoa khỏi CSDL (Chỉ cho phép nếu CHƯA từng có đơn hàng nào)
     */
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->withCount('orderItems')->findOrFail($id);

        if ($product->order_items_count > 0) {
            return redirect()->route('admin.products.index', ['status' => 'trashed'])->with('error', "Không thể xóa vĩnh viễn mẫu hoa '{$product->name}' vì đã từng phát sinh {$product->order_items_count} đơn hàng trong lịch sử kinh doanh!");
        }

        $product->forceDelete();
        return redirect()->route('admin.products.index', ['status' => 'trashed'])->with('success', "Đã xóa vĩnh viễn mẫu hoa khỏi cơ sở dữ liệu!");
    }
}
