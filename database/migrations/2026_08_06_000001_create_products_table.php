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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Core product info
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('vendor_product_name')->nullable();
            $table->text('description')->nullable();
            $table->text('info')->nullable();

            // Pricing
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('deliverycharge', 10, 2)->default(0.00);
            $table->decimal('vendorprice', 10, 2)->default(0.00);
            $table->decimal('vendordeliveryprice', 10, 2)->default(0.00);
            $table->boolean('more_price')->nullable();

            // Status & media
            $table->boolean('isactive')->default(true);
            $table->string('imgurl')->nullable();
            $table->boolean('more_img')->nullable();
            $table->text('more_desc')->nullable();

            // SEO
            $table->string('metatitle')->nullable();
            $table->text('metadesc')->nullable();
            $table->text('metakeyword')->nullable();
            $table->string('metaurl')->nullable();

            // Foreign key references (stored as plain integers, FK constraints optional)
            $table->unsignedBigInteger('cid')->nullable()->comment('Company ID');
            $table->unsignedBigInteger('vid')->nullable()->comment('Vendor ID');
            $table->unsignedBigInteger('cat_id')->nullable()->comment('Category ID');
            $table->unsignedBigInteger('subcat_id')->nullable()->comment('Sub-category ID');
            $table->unsignedBigInteger('brand_id')->nullable()->comment('Brand ID');

            // Product identifiers
            $table->string('use_type')->nullable();
            $table->string('vendor_code')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->string('barcode')->nullable();
            $table->string('model_number')->nullable();
            $table->string('manufacturer_part_number')->nullable();

            // Rating & engagement
            $table->decimal('ratingvalue', 3, 2)->default(0.00);
            $table->unsignedInteger('reviewcount')->default(0);
            $table->unsignedInteger('viewed')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
