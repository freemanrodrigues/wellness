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
        Schema::create('vendor_product_management', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable()->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('info')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('imgurl')->nullable();
            $table->unsignedBigInteger('vid')->nullable()->comment('Vendor ID');
            $table->unsignedBigInteger('cat_id')->nullable()->comment('Category ID');
            $table->unsignedBigInteger('subcat_id')->nullable()->comment('Subcategory ID');
            $table->unsignedBigInteger('brand_id')->nullable()->comment('Brand ID');
            $table->string('vendor_code')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_product_management');
    }
};
