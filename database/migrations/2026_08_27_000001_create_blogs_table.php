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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('url', 191)->unique();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('tags', 255)->nullable();
            $table->foreignId('cat_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->boolean('status')->default(true);
            $table->boolean('page_show')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
