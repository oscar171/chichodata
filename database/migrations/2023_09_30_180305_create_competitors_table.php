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
        Schema::create('competitors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->constrainted();
            $table->bigInteger('retail_product_id');
            $table->bigInteger('store_id');
            $table->string('store_name');
            $table->string('name');
            $table->string('sku');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('url');
            $table->string('image_url');
            $table->string('status');
            $table->datetime('created_retailer');
            $table->datetime('updated_retailer');
            $table->date('extracted');
            $table->float('lowest_price', 8, 2);
            $table->float('offer_price', 8, 2)->nullable();
            $table->float('normal_price', 8, 2)->nullable();
            $table->string('warehouse_name');
            $table->string('warehouse_id');
            $table->string('match_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitors');
    }
};
