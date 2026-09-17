<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tạo tài khoản Quản trị viên (Admin)
        User::updateOrCreate(
            ['email' => 'admin@flowershop.vn'],
            [
                'name' => 'Trần Quản Trị (Admin)',
                'password' => Hash::make('admin123'),
                'phone' => '0988888888',
                'address' => 'Số 1 Đại Cồ Việt, Hai Bà Trưng, Hà Nội',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Tạo tài khoản Khách hàng mẫu
        User::updateOrCreate(
            ['email' => 'khachhang@gmail.com'],
            [
                'name' => 'Nguyễn Thị Hoa',
                'password' => Hash::make('password123'),
                'phone' => '0912345678',
                'address' => '123 Đường Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh',
                'role' => 'customer',
                'email_verified_at' => now(),
            ]
        );
    }
}
