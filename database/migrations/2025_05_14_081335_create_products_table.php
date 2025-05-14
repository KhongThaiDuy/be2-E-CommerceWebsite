<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id'); // Mã sản phẩm, khóa chính, tự động tăng
            $table->string('product_name'); // Tên sản phẩm
            $table->text('description')->nullable(); // Mô tả sản phẩm
            $table->double('price'); // Giá sản phẩm
            $table->integer('quantity'); // Số lượng sản phẩm
            $table->unsignedBigInteger('category_id'); // Mã danh mục, khóa ngoại
            $table->string('image1')->nullable(); // Ảnh sản phẩm 1
            $table->string('image2')->nullable(); // Ảnh sản phẩm 2
            $table->string('image3')->nullable(); // Ảnh sản phẩm 3
            $table->tinyInteger('rating')->default(0); // Điểm đánh giá sản phẩm

            // Định nghĩa khóa ngoại
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');

            $table->timestamps(); // created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
