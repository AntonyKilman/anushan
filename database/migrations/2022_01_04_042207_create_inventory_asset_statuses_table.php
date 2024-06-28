<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryAssetStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_asset_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchased_product_id');
            $table->foreign('purchased_product_id')->references('id')->on('inventory_permanent_assets');
            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('inventory_asset_status_types');
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
        Schema::dropIfExists('inventory_asset_statuses');
    }
}
