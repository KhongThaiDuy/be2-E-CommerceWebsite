<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Tạo một số danh mục mẫu
        Category::create([
            'category_name' => 'Electronics',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Category::create([
            'category_name' => 'Furniture',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Category::create([
            'category_name' => 'Clothing',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
