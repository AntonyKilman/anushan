<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventorySellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_sellers', function (Blueprint $table) {
            $table->id();
            $table->string('seller_name');
            $table->string('seller_reg_no')->nullable();
            $table->string('seller_address');
            $table->integer('contact_no')->nullable();
            $table->integer('mobile_no')->nullable();
            $table->string('seller_img_1')->nullable();
            $table->string('seller_img_2')->nullable();
            $table->string('seller_img_3')->nullable();
            $table->unsignedBigInteger('seller_type_id');
            $table->foreign('seller_type_id') ->references('id') ->on('inventory_seller_types');
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
        Schema::dropIfExists('inventory_sellers');
    }
}
