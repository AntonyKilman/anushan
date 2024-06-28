<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPermanentAssetTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_permanent_asset_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->foreign('purchase_id')->references('id')->on('inventory_purchase_orders');
            $table->unsignedBigInteger('permanent_assets_id');
            $table->foreign('permanent_assets_id')->references('id')->on('inventory_permanent_assets');
            $table->unsignedBigInteger('department_id');    //department table
            $table->foreign('department_id')->references('id')->on('departments');
            $table->integer('quantity');
            $table->date('date');
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
        Schema::dropIfExists('inventory_permanent_asset_transfers');
    }
}
