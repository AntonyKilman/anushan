
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodcityProductReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foodcity_product_returns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('food_city_products')->onDelete('cascade');
            $table->unsignedBigInteger('purchase_order_id');
            $table->foreign('purchase_order_id')->references('id')->on('inventory_purchase_orders');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('inventory_products');
            $table->integer('return_qty');
            $table->unsignedBigInteger('return_reason_id');
            $table->foreign('return_reason_id')->references('id')->on('inventory_return_reasons');
            $table->text('description')->nullable();
            $table->integer('user_id');
            $table->integer('status');
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
        Schema::dropIfExists('foodcity_product_returns');
    }
}
