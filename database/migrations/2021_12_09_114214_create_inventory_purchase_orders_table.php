<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('pur_ord_bill_no')->nullable();
            $table->integer('pur_ord_amount');
            $table->string('pur_ord_cash')->nullable();
            $table->string('pur_ord_cheque')->nullable();
            $table->string('pur_ord_cheque_no')->nullable();
            $table->string('pur_ord_cheque_date')->nullable();
            $table->string('pur_ord_online_or_card')->nullable();
            $table->string('pur_ord_reference_no')->nullable();
            $table->string('pur_ord_credit')->nullable();
            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('inventory_sellers');
            $table->string('bill_img_1')->nullable();
            $table->string('bill_img_2')->nullable();
            $table->string('bill_img_3')->nullable();
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
        Schema::dropIfExists('inventory_purchase_orders');
    }
}
