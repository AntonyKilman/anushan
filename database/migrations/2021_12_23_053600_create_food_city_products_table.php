<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodCityProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_city_products', function (Blueprint $table) {
            $table-> id();
            $table-> string('name');
            $table->bigInteger('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('inventory_products')->onDelete('cascade');
            $table-> string('image')->nullable();
            $table-> double('purchase_price');
            $table-> double('sales_price')->nullable();
            $table-> double('discount_percentage')->nullable();
            $table-> double('discount_amount')->nullable();
            $table-> string('product_code');
            $table-> string('bar_code')->nullable();
            $table-> integer('quantity');
            $table-> integer('status');
            $table-> string('scale_id');
            $table-> date('expire_date')->nullable();
            $table-> integer('sub_category_id');
            $table-> integer('transection_id');
            $table-> integer('purchase_order_id');
            $table-> integer('user_id');
            $table-> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('food_city_products');
    }
}
