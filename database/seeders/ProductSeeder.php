<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Lấy category_id hợp lệ từ bảng categories
        $electronicsCategory = Category::where('category_name', 'Electronics')->first();
        $furnitureCategory = Category::where('category_name', 'Furniture')->first();
        $clothingCategory = Category::where('category_name', 'Clothing')->first();

        // Chèn sản phẩm vào các danh mục khác nhau
        Product::create([
            'product_name' => 'Laptop XYZ',
            'category_id' => $electronicsCategory->category_id,  // Sử dụng category_id hợp lệ
            'price' => 899.35,
            'quantity' => 10,
            'image1' => 'https://via.placeholder.com/640x480.png/008811?text=Electronics',
            'image2' => 'https://via.placeholder.com/640x480.png/004455?text=Laptop',
            'image3' => 'https://via.placeholder.com/640x480.png/0088ff?text=XYZ',
            'rating' => 4.5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Product::create([
            'product_name' => 'Sofa ABC',
            'category_id' => $furnitureCategory->category_id,
            'price' => 299.99,
            'quantity' => 5,
            'image1' => 'https://via.placeholder.com/640x480.png/008811?text=Furniture',
            'image2' => 'https://via.placeholder.com/640x480.png/004455?text=Sofa',
            'image3' => 'https://via.placeholder.com/640x480.png/0088ff?text=ABC',
            'rating' => 4.0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Product::create([
            'product_name' => 'T-Shirt PQR',
            'category_id' => $clothingCategory->category_id,
            'price' => 29.99,
            'quantity' => 20,
            'image1' => 'https://via.placeholder.com/640x480.png/008811?text=Clothing',
            'image2' => 'https://via.placeholder.com/640x480.png/004455?text=T-Shirt',
            'image3' => 'https://via.placeholder.com/640x480.png/0088ff?text=PQR',
            'rating' => 4.2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
