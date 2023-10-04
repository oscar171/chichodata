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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->bigInteger('store_id');
            $table->string('store_name');
            $table->string('name');
            $table->string('sku');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('url');
            $table->string('image_url');
            $table->string('status');
            $table->datetime('created_cocacola');
            $table->datetime('updated_cocacola');
            $table->date('extracted');
            $table->float('lowest_price', 8, 2);
            $table->float('offer_price', 8, 2);
            $table->float('normal_price', 8, 2)->nullable();
            $table->string('warehouse_name');
            $table->string('warehouse_id');
            $table->timestamps();
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
