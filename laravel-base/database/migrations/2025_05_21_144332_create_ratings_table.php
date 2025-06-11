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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->comment('Điểm đánh giá từ 1 đến 5');
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->text('reply')->nullable()->comment('Cửa hàng trả lời');
            $table->timestamp('reply_at')->nullable()->comment('Thời gian trả lời');
            $table->timestamps();
            $table->softDeletes();

            // Mỗi user chỉ được đánh giá 1 lần cho 1 biến thể trong 1 đơn hàng
            $table->unique(['user_id', 'product_id', 'product_variant_id'], 'unique_rating_per_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};