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
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers');
            $table->foreignId('rider_id')->nullable()->constrained('riders');
            $table->boolean('rider_team_only')->nullable();
            $table->decimal('shipping_fee', 8, 2);
            $table->decimal('discount', 8, 2);
            $table->decimal('total_item_price', 8, 2);
            $table->decimal('final_price', 8, 2);
            $table->string('address')->nullable();
            $table->string('note')->nullable();
            $table->unsignedTinyInteger('status');
            $table->string('delivery_image')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->timestamps();
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