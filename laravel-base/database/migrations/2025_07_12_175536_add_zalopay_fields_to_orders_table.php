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
        Schema::table('orders', function (Blueprint $table) {
            // Thêm order_number nếu chưa có
            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number')->nullable();
            }
            
            // Thêm các trường tài chính bổ sung
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('orders', 'tax_amount')) {
                $table->decimal('tax_amount', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('orders', 'shipping_amount')) {
                $table->decimal('shipping_amount', 15, 2)->default(0);
            }
            
            // Thêm notes nếu chưa có note
            if (!Schema::hasColumn('orders', 'notes') && !Schema::hasColumn('orders', 'note')) {
                $table->text('notes')->nullable();
            }
            
            // Thêm các trường ZaloPay
            $table->string('zalopay_app_trans_id')->nullable();
            $table->string('zalopay_zp_trans_token')->nullable();
            $table->string('zalopay_response_code')->nullable();
            $table->string('zalopay_app_id')->nullable();
            $table->text('zalopay_embed_data')->nullable();
            
            // Thêm index cho hiệu suất
            $table->index('zalopay_app_trans_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Xóa index trước
            $table->dropIndex(['zalopay_app_trans_id']);
            
            // Xóa các cột ZaloPay
            $table->dropColumn([
                'zalopay_app_trans_id',
                'zalopay_zp_trans_token', 
                'zalopay_response_code',
                'zalopay_app_id',
                'zalopay_embed_data'
            ]);
            
            // Xóa các cột khác nếu đã thêm
            if (Schema::hasColumn('orders', 'shipping_amount')) {
                $table->dropColumn('shipping_amount');
            }
            if (Schema::hasColumn('orders', 'tax_amount')) {
                $table->dropColumn('tax_amount');
            }
            if (Schema::hasColumn('orders', 'subtotal')) {
                $table->dropColumn('subtotal');
            }
            if (Schema::hasColumn('orders', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('orders', 'order_number')) {
                $table->dropColumn('order_number');
            }
        });
    }
};