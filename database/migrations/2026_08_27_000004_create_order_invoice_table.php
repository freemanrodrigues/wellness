<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_invoice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('totalamount', 10, 2)->nullable();
            $table->decimal('orderdiscount', 10, 2)->nullable();
            $table->decimal('promo_discount', 10, 2)->nullable();
            $table->decimal('deliverycharge', 10, 2)->nullable();
            $table->string('gateway_id', 255)->nullable();
            $table->string('orderstatus', 50)->nullable();
            $table->string('sess_id', 255)->nullable();
            $table->string('shopflag', 50)->nullable();
            $table->unsignedBigInteger('affiliate_id')->nullable();
            $table->decimal('affiliate_commission', 10, 2)->nullable();
            $table->string('error_code', 100)->nullable();
            $table->text('error_message')->nullable();
            $table->string('cardname', 255)->nullable();
            $table->string('cardnumber', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_invoice');
    }
};
