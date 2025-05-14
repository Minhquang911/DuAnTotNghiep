<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name');
            $table->string('user_email');
            $table->string('user_phone');
            $table->text('shipping_address');

            $table->decimal('total_amount', 12, 2)->default(0);

            $table->foreignId('status_id')->nullable()->constrained('statuses')->onDelete('set null');

            $table->string('payment_method');
            $table->string('promotion_code')->nullable();
            $table->decimal('promotion_discount', 12, 2)->default(0);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('final_amount', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
