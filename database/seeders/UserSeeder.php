<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        User::factory()->count(20)->create();

        // (Tùy chọn) Thêm một số người dùng cố định để test
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
            'remember_token' => null,
            'verify_email' => true,
            'is_active' => true,
            'is_delete' => false,
            'group_role' => 'admin',
            'last_login_at' => now(),
            'last_login_ip' => '192.168.1.1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::create([
            'name' => 'Normal User',
            'email' => 'user@example.com',
            'password' => Hash::make('123456'),
            'remember_token' => null,
            'verify_email' => false,
            'is_active' => true,
            'is_delete' => false,
            'group_role' => 'user',
            'last_login_at' => null,
            'last_login_ip' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
