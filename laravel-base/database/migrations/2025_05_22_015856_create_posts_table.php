<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index(); // người tạo bài viết (có thể sửa tên theo hệ thống user của bạn)
            $table->string('title'); // tiêu đề bài viết
            $table->string('slug')->unique(); // đường dẫn thân thiện
            $table->longText('content'); // nội dung bài viết
            $table->string('image')->nullable(); // ảnh đại diện bài viết (nếu có)
            $table->boolean('is_published')->default(false); // đã đăng chưa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};