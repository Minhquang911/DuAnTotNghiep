<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id'); // Khóa chính
            $table->string('name', 100);
            $table->string('email', 150)->unique(); // Email duy nhất
            $table->string('password', 255); // Mật khẩu mã hóa
            $table->string('phone', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('role', 50)->default('customer'); // Mặc định là customer
            $table->timestamp('created_at')->useCurrent(); // Thời điểm đăng ký
            $table->timestamp('updated_at')->useCurrent()->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};


