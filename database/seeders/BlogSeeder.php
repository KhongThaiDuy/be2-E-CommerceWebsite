<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;

class BlogSeeder extends Seeder
{
    public function run()
    {
        // Lấy user_id hợp lệ từ bảng users
        $user = User::first();  // Lấy user đầu tiên từ bảng users

        if ($user) {
            // Chèn một số bài viết mẫu vào bảng blogs
            Blog::create([
                'title' => 'Lợi ích của công nghệ',
                'content' => 'Công nghệ ngày càng phát triển, mang lại nhiều lợi ích cho cuộc sống...',
                'image' => 'https://via.placeholder.com/640x480.png/008811?text=Technology',
                'user_id' => $user->id,  // Gán đúng user_id
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Blog::create([
                'title' => 'Cách chọn sofa phù hợp',
                'content' => 'Khi chọn sofa, cần lưu ý đến chất liệu, màu sắc, và kiểu dáng...',
                'image' => 'https://via.placeholder.com/640x480.png/004455?text=Furniture',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Blog::create([
                'title' => 'Tầm quan trọng của thời trang',
                'content' => 'Thời trang không chỉ giúp chúng ta tự tin mà còn phản ánh cá tính...',
                'image' => 'https://via.placeholder.com/640x480.png/0088ff?text=Fashion',
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            echo "Không tìm thấy người dùng để gán vào blog.\n";
        }
    }
}
