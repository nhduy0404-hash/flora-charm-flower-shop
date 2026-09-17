<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'GIAM99',
                'discount_type' => 'percentage',
                'discount_value' => 99.00,
                'min_order_amount' => 0,
                'expires_at' => Carbon::now()->addYears(2),
                'usage_limit' => 10000,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'FLORA99',
                'discount_type' => 'percentage',
                'discount_value' => 99.00,
                'min_order_amount' => 0,
                'expires_at' => Carbon::now()->addYears(2),
                'usage_limit' => 10000,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'WELCOME10',
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'min_order_amount' => 0,
                'expires_at' => Carbon::now()->addYears(1),
                'usage_limit' => 5000,
                'used_count' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}