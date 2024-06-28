<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryEquipmentTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_equipment_transfer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->foreign('purchase_id')->references('id')->on('inventory_purchase_orders');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('inventory_products');
            $table->unsignedBigInteger('department_id');    //department table
            $table->foreign('department_id')->references('id')->on('departments');
            $table->unsignedBigInteger('employee_id');   
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->integer('quantity');
            $table->date('purchaseDate');
            $table->integer('noOfDays');
            $table->longText('reason');
            $table->string('userEnter');
            $table->string('status');
            $table->longText('discription')->nullable();
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
        Schema::dropIfExists('inventory_equipment_transfer');
    }
}
