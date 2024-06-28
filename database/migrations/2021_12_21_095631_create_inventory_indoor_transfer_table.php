<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryIndoorTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_indoor_transfer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->foreign('purchase_id')->references('id')->on('inventory_purchase_orders');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('inventory_products');
            $table->String('quantity');
            $table->String('transfer_quantity');
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on('inventory_departments');
            $table->integer('user_id');
            $table->date('exDate')->nullable();
            $table->String('approved_by')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('inventory_indoor_transfer');
    }
}
