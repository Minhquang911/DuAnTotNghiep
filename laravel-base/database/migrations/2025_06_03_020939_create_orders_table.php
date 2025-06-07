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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_code', 20)->unique(); // mã đơn hàng
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // Thông tin khách hàng
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone', 10);
            $table->string('customer_address');
            // Thông tin địa chỉ chi tiết
            $table->string('customer_province')->nullable();
            $table->string('customer_district')->nullable();
            $table->string('customer_ward')->nullable();

            // Tổng tiền hàng, phí ship, giảm giá, số tiền cần thanh toán
            $table->decimal('total_amount', 12, 2);                // tổng tiền hàng
            $table->decimal('shipping_fee', 10, 2)->default(0);    // phí vận chuyển
            $table->decimal('discount_amount', 10, 2)->default(0); // giảm giá
            $table->decimal('amount_due', 12, 2);                  // tổng phải trả

            // Phương thức và trạng thái thanh toán
            $table->enum('payment_method', ['cod', 'bank_transfer'])->default('cod'); // Phương thức thanh toán
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid'); // Trạng thái thanh toán
            $table->timestamp('paid_at')->nullable(); // Thời gian thanh toán

            // Trạng thái đơn hàng duy nhất (gộp)
            $table->enum('status', [
                'pending',      // Chờ xác nhận
                'processing',   // Đã xác nhận - đang xử lý
                'delivering',   // Đang giao
                'completed',    // Đã giao thành công
                'finished',     // Hoàn thành toàn bộ (kết thúc quy trình)
                'cancelled',    // Đã hủy
                'failed'        // Giao hàng thất bại
            ])->default('pending');            

            // Khuyến mãi nếu có
            $table->string('coupon_code')->nullable();

            $table->text('note')->nullable();
            $table->timestamp('ordered_at')->nullable();

            $table->timestamps();

            // Index hỗ trợ truy vấn nhanh
            $table->index('customer_phone');
            $table->index('status');
            $table->index('order_code');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            // Nếu có sản phẩm biến thể:
            $table->unsignedBigInteger('product_variant_id')->nullable();
        
            $table->string('sku')->nullable();                      // mã SKU tại thời điểm đặt (nếu có)
            $table->string('product_name');                         // tên tại thời điểm đặt
            $table->string('product_variant_name')->nullable();     // tên biến thể tại thời điểm đặt
            $table->string('product_image')->nullable();            // ảnh sản phẩm tại thời điểm đặt
            $table->decimal('price', 10, 2);                        // đơn giá lúc đặt
            $table->integer('quantity');
            $table->decimal('total', 12, 2);                        // tổng giá = price * quantity

            // Đã đánh giá chưa
            $table->boolean('is_rated')->default(false);
        
            $table->timestamps();

            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('restrict');
        });        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};