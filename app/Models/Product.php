<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Đặt tên bảng nếu tên bảng không theo chuẩn Laravel (số nhiều, chữ cái thường)
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    // Các trường có thể được gán giá trị (fillable)
    protected $fillable = [
        'product_name',
        'description',
        'price',
        'quantity',
        'category_id',
        'image1',
        'image2',
        'image3',
        'rating',
    ];

    // Định nghĩa quan hệ với bảng categories (một sản phẩm thuộc một danh mục)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
}
