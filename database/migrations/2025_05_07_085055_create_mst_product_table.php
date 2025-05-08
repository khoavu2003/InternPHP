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
        Schema::create('mst_product', function (Blueprint $table) {
            $table->string('product_id')->primary(); // Khóa chính là string
            $table->string('product_name');
            $table->string('product_image')->nullable();
            $table->decimal('product_price', 10, 2); // Giá sản phẩm với 2 chữ số thập phân
            $table->boolean('is_sales')->default(false);
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_product');
    }
};
