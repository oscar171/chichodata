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
        Schema::create('categorie_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categorie_id')->constrainted();
            $table->unsignedBigInteger('product_id')->constrainted();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorie_product');
    }
};
