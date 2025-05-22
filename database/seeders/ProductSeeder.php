<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();

        for ($i = 1; $i <= 30; $i++) {
            $category = $categories->random(); // chọn ngẫu nhiên 1 category

            Product::create([
                'product_name' => "Product $i",
                'category_id' => $category->category_id,
                'price' => rand(10, 1000) + rand(0, 99)/100,
                'quantity' => rand(1, 50),
                'image1' => 'assets/images/product-1.jpg',
                'image2' => 'assets/images/product-2.jpg',
                'image3' => 'assets/images/product-3.jpg',
                'rating' => rand(30, 50) / 10,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
