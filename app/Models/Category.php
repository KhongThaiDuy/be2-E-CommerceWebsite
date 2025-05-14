<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Đặt tên bảng (Laravel tự động hiểu bảng tên số nhiều, nhưng nếu em muốn ghi rõ thì khai báo)
    protected $table = 'categories';

    // Đặt khóa chính (Laravel mặc định khóa chính là id, nhưng ở đây em đã đặt là category_id)
    protected $primaryKey = 'category_id';

    // Bỏ qua các trường không cần thiết nếu có (như 'updated_at' nếu không muốn Laravel tự động cập nhật)
    public $timestamps = true;

    // Cập nhật các trường có thể được gán giá trị bằng cách sử dụng $fillable
    protected $fillable = [
        'category_name',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}
