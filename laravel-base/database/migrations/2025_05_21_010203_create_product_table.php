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
        // Tạo bảng Định dạng sách
        Schema::create('formats', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Tên định dạng sách (VD: Bìa mềm, Bìa cứng, Ebook)');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Tên ngôn ngữ (VD: Tiếng Việt, Tiếng Anh)');
            $table->string('code')->unique()->comment('Mã ngôn ngữ (VD: vi, en)');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tạo bảng Thông tin chung về sách
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); 
            $table->string('author')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('publisher_id');
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->date('published_at')->nullable();
            $table->string('isbn')->nullable(); // mã sách
            $table->unsignedBigInteger('view_count')->default(0); // lượt xem
            $table->unsignedBigInteger('order_count')->default(0); // lượt đã mua
            $table->boolean('is_active')->default(true);

            // Bổ sung các trường kích thước, trọng lượng, số trang
            $table->decimal('length_cm', 6, 2)->nullable()->comment('Chiều dài (cm)');
            $table->decimal('width_cm', 6, 2)->nullable()->comment('Chiều rộng (cm)');
            $table->unsignedInteger('weight_g')->nullable()->comment('Trọng lượng (g)');
            $table->unsignedInteger('page_count')->nullable()->comment('Số trang');

            // Trạng thái sách
            $table->boolean('is_hot')->default(false); // Sách hot
            $table->boolean('is_new')->default(false); // Sách mới
            $table->boolean('is_best_seller')->default(false); // Sách bán chạy
            $table->boolean('is_recommended')->default(false); // Sách được đề xuất
            $table->boolean('is_featured')->default(false); // Sách nổi bật
            $table->boolean('is_promotion')->default(false); // Sách khuyến mãi

            $table->timestamps();
            $table->softDeletes();
        
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('cascade');
        });

        // Tạo bảng Biến thể của sách
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('format_id');   // bìa mềm, bìa cứng, ebook...
            $table->unsignedBigInteger('language_id'); // tiếng Việt, tiếng Anh...
            $table->string('sku')->unique();           // mã phân biệt biến thể
            $table->decimal('price', 10, 2);
            $table->decimal('promotion_price', 10, 2)->nullable(); // giá khuyến mãi
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('format_id')->references('id')->on('formats');
            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('formats');
        Schema::dropIfExists('languages');
    }
};