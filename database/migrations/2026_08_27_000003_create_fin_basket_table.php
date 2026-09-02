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
        Schema::create('fin_basket', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 255)->nullable();
            $table->integer('pid')->nullable();
            $table->integer('cid')->nullable();
            $table->integer('vid')->nullable();
            $table->string('product_name', 255)->nullable();
            $table->integer('qty')->default(1);
            $table->decimal('product_price', 10, 2)->nullable();
            $table->decimal('vendor_price', 10, 2)->nullable();
            $table->decimal('pdiscount', 10, 2)->nullable();
            $table->decimal('deliverycharge', 10, 2)->nullable();
            $table->decimal('vendor_deliverycharge', 10, 2)->nullable();
            $table->string('sess_id', 255)->nullable();
            $table->string('user_email', 255)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_ip', 45)->nullable();
            $table->string('s_firstname', 255)->nullable();
            $table->string('s_lastname', 255)->nullable();
            $table->string('s_address1', 255)->nullable();
            $table->string('s_address2', 255)->nullable();
            $table->string('s_landmark', 255)->nullable();
            $table->string('s_city', 255)->nullable();
            $table->string('s_state', 255)->nullable();
            $table->string('s_pincode', 50)->nullable();
            $table->integer('s_country_id')->nullable();
            $table->string('s_email', 255)->nullable();
            $table->string('s_phone', 50)->nullable();
            $table->text('cardmessage')->nullable();
            $table->date('deliverydate')->nullable();
            $table->string('basketflag', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fin_basket');
    }
};
