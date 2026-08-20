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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('countryname', 191)->nullable();
            $table->string('shortname', 191)->nullable();
            $table->char('isocode', 3)->nullable();
            $table->string('tel_countrycode', 16);
            $table->boolean('active');
            $table->char('gmt', 8);
            $table->char('currencycode', 8);
            $table->double('currencyrate', 8, 2);
            $table->integer('timezoneid');
            $table->string('isocode3', 3)->nullable()->default(null);
            $table->string('currencysign', 3)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
