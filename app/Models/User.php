<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Str;


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
        'updated_at' => 'datetime',
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'user_id');
    }

    public function getHashIdAttribute()
    {
        return Hashids::encode($this->id);
    }
    
    public function resolveRouteBinding($value, $field = null)
    {
        $decoded = Hashids::decode($value);
        if (count($decoded) === 0) {
            abort(404);
        }

        return $this->where('id', $decoded[0])->firstOrFail();
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            // Nếu chưa có token, gán một mã ngẫu nhiên dài
            if (empty($user->token)) {
                $user->token = Str::random(64);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'token'; // Bind route model theo token thay vì id
    }

}
