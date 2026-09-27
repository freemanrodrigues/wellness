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
        Schema::create('faq_and_question_answers', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['faq', 'question'])->default('faq');
            
            // Entity associations (all nullable so FAQ can target a product, category, subcategory, brand, or static page)
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->foreignId('subcategory_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('cascade');
            $table->string('page_name')->nullable(); // e.g. 'home', 'about', 'contact', 'terms', 'privacy'

            // User / Visitor information for Q&A
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name')->nullable();
            $table->string('email')->nullable();

            // Question & Answer text
            $table->text('question');
            $table->text('answer')->nullable();

            // Status and Sort Order
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_and_question_answers');
    }
};
