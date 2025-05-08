<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = \App\Models\Product::class;

    protected $productCounter = 0;

    public function definition(): array
    {
        $this->productCounter++;

        // Tạo product_name trước
        $productName = $this->faker->word();
        
        // Lấy chữ cái đầu của product_name và chuyển thành chữ hoa
        $prefix = strtoupper(substr($productName, 0, 1));

        // Tạo 9 chữ số với counter
        $nineDigits = str_pad($this->productCounter, 9, '0', STR_PAD_LEFT);

        return [
            'product_id' => $prefix . $nineDigits, // Ví dụ: Apple -> A000000001
            'product_name' => $productName,
            'product_image' => $this->faker->imageUrl(),
            'product_price' => $this->faker->randomFloat(1, 10, 1000000),
            'is_sales' => $this->faker->boolean(30),
            'description' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
