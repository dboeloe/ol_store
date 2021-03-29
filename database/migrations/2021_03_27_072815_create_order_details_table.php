<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->bigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->bigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->float('order_price');
            $table->integer('order_qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
