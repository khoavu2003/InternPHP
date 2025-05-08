<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory()->count(50)->create();

        // (Tùy chọn) Thêm một số bản ghi cố định để test
        Customer::create([
            'customer_name' => 'Nguyễn Văn A',
            'email' => 'nguyen.a@example.com',
            'tel_num' => '0901234567',
            'address' => 'Hà Nội, Việt Nam',
            'is_active' => true,
            'is_delete' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
