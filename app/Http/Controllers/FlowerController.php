<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class FlowerController extends Controller
{
    /**
     * Danh sách hoa có lọc, tìm kiếm và phân trang
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        // 1. Lọc theo danh mục
        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // 2. Tìm kiếm theo tên hoa
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // 3. Lọc theo khoảng giá
        if ($request->filled('price_range')) {
            $range = $request->input('price_range');
            switch ($range) {
                case 'under_500':
                    $query->where('price', '<', 500000);
                    break;
                case '500_1000':
                    $query->whereBetween('price', [500000, 1000000]);
                    break;
                case 'above_1000':
                    $query->where('price', '>', 1000000);
                    break;
            }
        }

        // 4. Sắp xếp
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('flowers.index', compact('products', 'categories'));
    }

    /**
     * Chi tiết sản phẩm hoa
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'reviews.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Các bó hoa cùng danh mục (Related flowers)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('flowers.show', compact('product', 'relatedProducts'));
    }

    /**
     * API gợi ý tìm kiếm tức thì (Search Autocomplete)
     */
    public function searchApi(Request $request)
    {
        $keyword = $request->query('q', '');
        if (strlen($keyword) < 2) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $results = Product::where('is_active', true)
            ->where('name', 'LIKE', "%{$keyword}%")
            ->take(5)
            ->get(['id', 'name', 'slug', 'price', 'sale_price', 'thumbnail']);

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }
}
