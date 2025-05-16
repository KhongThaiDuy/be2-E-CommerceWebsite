<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // 'user_id' INT(10) - Khóa chính, tự động tăng
            $table->string('username',191)->unique(); // 'username' VARCHAR(255) - Tên đăng nhập, duy nhất
            $table->string('password'); // 'password' VARCHAR(255) - Mật khẩu, đã hash
            $table->string('email',191)->unique()->nullable(); // 'email' VARCHAR(50) - Email, duy nhất, có thể null
            $table->string('full_name', 100)->change(); // 'full_name' VARCHAR(255) - Họ và tên đầy đủ
            $table->string('address', 255)->nullable()->change(); // 'address' VARCHAR(255) - Địa chỉ, có thể null
            $table->string('phone', 10)->nullable(); // 'phone' INT(10) - Số điện thoại, có thể null, độ dài 10
            $table->enum('role', ['customer', 'admin'])->default('customer'); // 'role' ENUM('customer', 'admin') - Vai trò, mặc định là 'customer'
            $table->string('image')->nullable(); // 'image' VARCHAR(255) - Đường dẫn hình ảnh, có thể null
            $table->timestamp('created_at')->useCurrent(); // 'created_at' TIMESTAMP - Thời gian tạo, tự động cập nhật
            $table->timestamp('updated_at')->nullable(); // Thêm trường updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
