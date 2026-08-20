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
        Schema::create('baskets', function (Blueprint $table) {
            $table->id();
            $table->integer('pid');
            $table->integer('cid')->nullable();
            $table->integer('vid');
            $table->string('product_name');
            $table->integer('qty');
            $table->decimal('prodprice', 10, 2);
            $table->decimal('vendor_price', 10, 2)->nullable();
            $table->integer('multiple_price_id')->nullable();
            $table->string('mutiple_price_desc')->nullable();
            $table->decimal('pdiscount', 10, 2)->nullable();
            $table->decimal('deliverycharge', 10, 2)->nullable();
            $table->decimal('vendor_deliverycharge', 10, 2)->nullable();
            $table->string('sess_id')->nullable();
            $table->string('user_email')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_ip')->nullable();
            $table->string('s_firstname')->nullable();
            $table->string('s_lastname')->nullable();
            $table->string('s_address1')->nullable();
            $table->string('s_address2')->nullable();
            $table->string('s_landmark')->nullable();
            $table->string('s_city')->nullable();
            $table->string('s_state')->nullable();
            $table->string('s_pincode')->nullable();
            $table->integer('s_country_id')->nullable();
            $table->string('s_email')->nullable();
            $table->string('s_phone')->nullable();
            $table->text('cardmessage')->nullable();
            $table->integer('ocassionid')->nullable();
            $table->string('locationtype')->nullable();
            $table->date('deliverydate')->nullable();
            $table->char('basketflag', 1)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baskets');
    }
};
