<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            Category::create([
                'category_name' => "Category $i",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
