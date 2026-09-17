<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    /**
     * Danh sách tài khoản khách hàng (CRM)
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders')->withSum('orders', 'total_amount')->latest();

        if ($request->filled('keyword')) {
            $kw = trim($request->keyword);
            $query->where(function ($q) use ($kw) {
                $q->where('name', 'LIKE', "%{$kw}%")
                  ->orWhere('email', 'LIKE', "%{$kw}%")
                  ->orWhere('phone', 'LIKE', "%{$kw}%");
            });
        }

        $customers = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('customers'));
    }
}