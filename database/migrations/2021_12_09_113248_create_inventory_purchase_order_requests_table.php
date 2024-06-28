<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPurchaseOrderRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_purchase_order_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('inventory_products');
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on('inventory_departments');
            $table->integer('pur_req_qty');
            $table->string('pur_req_qty_type')->nullable();
            $table->longText('pur_req_des')->nullable();
            $table->longText('pur_req_reason')->nullable();
            $table->string('pur_req_approved_by')->nullable();
            $table->string('pur_req_status');
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
        Schema::dropIfExists('inventory_purchase_order_requests');
    }
}
