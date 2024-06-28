<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryOutdoorReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_outdoor_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
            $table->foreign('purchase_order_id')->references('id')->on('inventory_purchase_orders');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('inventory_products');
            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('inventory_sellers');
            $table->unsignedBigInteger('return_reason_id');
            $table->foreign('return_reason_id')->references('id')->on('inventory_return_reasons');
            $table->integer('qty');
            $table->integer('return_qty');
            $table->integer('user_id');
            $table->string('status');
            $table->integer('approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_outdoor_returns');
    }
}
