<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'password' => Hash::make('12345678'),
                'email' => 'admin@gmail.com',
                'full_name' => 'Admin User',
                'address' => 'Hanoi, Vietnam',
                'phone' => '0123456789',
                'role' => 'admin',
                'image' => 'assets/images/blog-1.jpg',
                'created_at' => now(),
            ],
            [
                'username' => 'john',
                'password' => Hash::make('12345678'),
                'email' => 'john@example.com',
                'full_name' => 'John Doe',
                'address' => 'Ho Chi Minh City',
                'phone' => '0987654321',
                'role' => 'customer',
                'image' => 'assets/images/blog-1.jpg',
                'created_at' => now(),
            ],
        ];

        // Thêm 10 user mới
        for ($i = 1; $i <= 20; $i++) {
            $users[] = [
                'username' => "user$i",
                'password' => Hash::make('12345678'),
                'email' => "user$i@gmail.com",
                'full_name' => "User $i",
                'address' => "City $i",
                'phone' => "09000000$i",
                'role' => 'customer',
                'image' => 'assets/images/blog-1.jpg',
                'created_at' => now(),
            ];
        }

        // Chèn hoặc cập nhật nếu tồn tại
        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['username' => $user['username']],
                $user
            );
        }
    }
}
