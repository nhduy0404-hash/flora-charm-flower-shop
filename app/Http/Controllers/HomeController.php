<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ website bán hoa
     */
    public function index()
    {
        // 1. Danh mục hoa nổi bật
        $categories = Category::where('is_active', true)->take(6)->get();

        // 2. Bó hoa nổi bật (Featured flowers)
        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->take(8)
            ->get();

        // 3. Bó hoa mới nhất (New arrivals)
        $newProducts = Product::with('category')
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('categories', 'featuredProducts', 'newProducts'));
    }
}
