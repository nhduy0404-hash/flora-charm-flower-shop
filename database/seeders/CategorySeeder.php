<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hoa Sinh Nhật',
                'slug' => 'hoa-sinh-nhat',
                'description' => 'Những bó hoa tươi thắm, rực rỡ mang lời chúc tuổi mới ngập tràn niềm vui và hạnh phúc.',
                'image' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=600&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Hoa Khai Trương',
                'slug' => 'hoa-khai-truong',
                'description' => 'Kệ hoa khai trương sang trọng, hồng phát mang lại tài lộc và may mắn cho gia chủ.',
                'image' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?w=600&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Hoa Tình Yêu',
                'slug' => 'hoa-tinh-yeu',
                'description' => 'Hoa hồng đỏ nồng nàn, hoa baby dịu dàng biểu tượng cho tình yêu vĩnh cửu và chung thủy.',
                'image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Lan Hồ Điệp',
                'slug' => 'lan-ho-diep',
                'description' => 'Chậu lan hồ điệp quý phái, đẳng cấp, hoa tươi bền lâu thích hợp biếu tặng đối tác.',
                'image' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?w=600&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Hoa Cưới & Cầm Tay',
                'slug' => 'hoa-cuoi-cam-tay',
                'description' => 'Bó hoa cưới tinh khôi, trang nhã đồng hành cùng cô dâu trong ngày trọng đại.',
                'image' => 'https://images.unsplash.com/photo-1527061011665-3652c757a4d4?w=600&q=80',
                'is_active' => true,
            ],
            [
                'name' => 'Hoa Chia Buồn',
                'slug' => 'hoa-chia-buon',
                'description' => 'Vòng hoa trang nghiêm, thành kính sẻ chia nỗi buồn và tiếc thương cùng gia quyến.',
                'image' => 'https://images.unsplash.com/photo-1508615039623-a25605d2b022?w=600&q=80',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
