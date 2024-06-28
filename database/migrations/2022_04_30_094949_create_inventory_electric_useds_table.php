<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryElectricUsedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_electric_useds', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('purchase_id');
            $table->double('used_qty');
            $table->string('qty_type')->nullable();
            $table->double('return_qty')->nullable();
            $table->double('host')->nullable();
            $table->integer('status')->nullable();
            $table->text('reason')->nullable();
            $table->string('user_id');
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
        Schema::dropIfExists('inventory_electric_useds');
    }
}
