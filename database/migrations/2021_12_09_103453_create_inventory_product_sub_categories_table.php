<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryProductSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_product_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->string('product_sub_cat_name');
            $table->longText('product_sub_cat_des')->nullable();
            $table->unsignedBigInteger('product_cat_id');
            $table->foreign('product_cat_id') ->references('id') ->on('inventory_product_categories');
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
        Schema::dropIfExists('inventory_product_sub_categories');
    }
}
