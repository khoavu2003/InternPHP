<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()->count(30)->create();

        // (Tùy chọn) Thêm một số sản phẩm cố định để test
        Product::create([
            'product_id' => 'P000000001', // Prefix 'P' + 9 số
            'product_name' => 'Laptop Dell',
            'product_image' => 'https://example.com/images/laptop.jpg',
            'product_price' => 1500.99,
            'is_sales' => true,
            'description' => 'Laptop cao cấp dành cho lập trình viên.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Product::create([
            'product_id' => 'P000000002',
            'product_name' => 'Điện thoại iPhone',
            'product_image' => 'https://example.com/images/iphone.jpg',
            'product_price' => 999.99,
            'is_sales' => false,
            'description' => 'Điện thoại thông minh thế hệ mới.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
