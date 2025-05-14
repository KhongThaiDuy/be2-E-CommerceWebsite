<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users'; // Chỉ định tên bảng (mặc dù thường không cần vì Laravel tự động nhận diện)

    protected $fillable = [
        'username', 'email', 'password', 'full_name', 'address', 'phone', 'role', 'image' // Các trường được phép gán giá trị khi tạo mới
    ];

    protected $hidden = [
        'password', 'remember_token', // Các trường sẽ bị ẩn khi lấy dữ liệu (ví dụ: không hiển thị password)
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'user_id');
    }
}
