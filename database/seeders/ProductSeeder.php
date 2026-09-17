<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sinhNhat = Category::where('slug', 'hoa-sinh-nhat')->first();
        $khaiTruong = Category::where('slug', 'hoa-khai-truong')->first();
        $tinhYeu = Category::where('slug', 'hoa-tinh-yeu')->first();
        $lanHoDiep = Category::where('slug', 'lan-ho-diep')->first();

        $products = [
            [
                'category_id' => $tinhYeu->id ?? 1,
                'name' => 'Bó Hoa Hồng Đỏ Lãng Mạn - Only You',
                'slug' => 'bo-hoa-hong-do-lang-man-only-you',
                'price' => 550000,
                'sale_price' => 480000,
                'stock_quantity' => 15,
                'thumbnail' => 'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?w=800&q=80',
                'description' => 'Bó hoa gồm 19 bông hồng Ecuador đỏ nhung tươi thắm kết hợp cùng hoa baby trắng tinh khôi, tượng trưng cho tình yêu nồng cháy và duy nhất.',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $sinhNhat->id ?? 1,
                'name' => 'Bó Hoa Hướng Dương Tươi Sáng - Nắng Ban Mai',
                'slug' => 'bo-hoa-huong-duong-tuoi-sang-nang-ban-mai',
                'price' => 450000,
                'sale_price' => 390000,
                'stock_quantity' => 20,
                'thumbnail' => 'https://images.unsplash.com/photo-1597848212624-a19eb35e2651?w=800&q=80',
                'description' => 'Hoa hướng dương rực rỡ mang nguồn năng lượng tích cực, lời chúc tuổi mới luôn vươn cao, thành công và ngập tràn may mắn.',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $tinhYeu->id ?? 1,
                'name' => 'Bó Hoa Baby Hồng Ngọt Ngào - Sweet Dream',
                'slug' => 'bo-hoa-baby-hong-ngot-ngao-sweet-dream',
                'price' => 420000,
                'sale_price' => null,
                'stock_quantity' => 12,
                'thumbnail' => 'https://images.unsplash.com/photo-1563245372-f21724e3856d?w=800&q=80',
                'description' => 'Bó hoa baby phun màu hồng pastel bồng bềnh như đám mây kẹo ngọt, thích hợp dành tặng bạn gái trong những dịp kỷ niệm nhẹ nhàng.',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'category_id' => $khaiTruong->id ?? 2,
                'name' => 'Kệ Hoa Khai Trương Phát Tài Phát Lộc',
                'slug' => 'ke-hoa-khai-truong-phat-tai-phat-loc',
                'price' => 1500000,
                'sale_price' => 1350000,
                'stock_quantity' => 8,
                'thumbnail' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?w=800&q=80',
                'description' => 'Kệ hoa 2 tầng sang trọng gồm hoa đồng tiền đỏ, hoa ly vàng và lan mokara, lời chúc kinh doanh hồng phát, mã đáo thành công.',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $lanHoDiep->id ?? 3,
                'name' => 'Chậu Lan Hồ Điệp Tím Quý Phái (5 Cành)',
                'slug' => 'chau-lan-ho-diep-tim-quy-phai-5-canh',
                'price' => 1800000,
                'sale_price' => 1650000,
                'stock_quantity' => 5,
                'thumbnail' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?w=800&q=80',
                'description' => 'Lan hồ điệp tím đại biểu cho sự thủy chung, vương giả và thanh lịch. Hoa bền từ 1 đến 2 tháng trong điều kiện mát mẻ.',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $sinhNhat->id ?? 1,
                'name' => 'Giỏ Hoa Tulip Hà Lan Đủ Màu - Spring Melody',
                'slug' => 'gio-hoa-tulip-ha-lan-du-mau-spring-melody',
                'price' => 850000,
                'sale_price' => 790000,
                'stock_quantity' => 10,
                'thumbnail' => 'https://images.unsplash.com/photo-1520763185298-1b434c919102?w=800&q=80',
                'description' => 'Giỏ hoa kết hợp từ hoa tulip nhập khẩu tươi tắn, hoa cúc mẫu đơn và hoa lá phụ nhập khẩu cao cấp.',
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
