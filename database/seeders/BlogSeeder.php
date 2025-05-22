<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;
use Faker\Factory as Faker;

class BlogSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $users = User::all(); // Lấy tất cả user

        if ($users->count() === 0) {
            echo "Không có user nào để gán blog.\n";
            return;
        }

        for ($i = 1; $i <= 50; $i++) {
            Blog::create([
                'title' => ucfirst($faker->sentence(rand(3, 6))),
                'content' => $faker->paragraphs(rand(2, 5), true),
                'image' => 'assets/images/blog-' . rand(1, 3) . '.jpg',
                'user_id' => $users->random()->id, // Gán ngẫu nhiên user
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
