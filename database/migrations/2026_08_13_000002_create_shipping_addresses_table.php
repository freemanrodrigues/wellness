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
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('s_firstname');
            $table->string('s_lastname');
            $table->string('s_address1');
            $table->string('s_address2')->nullable();
            $table->string('s_landmark')->nullable();
            $table->string('s_city');
            $table->string('s_state');
            $table->string('s_pincode');
            $table->integer('s_country_id');
            $table->string('s_email')->nullable();
            $table->string('s_phone');
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_delete')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};
