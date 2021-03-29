<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('stock_transactions', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->timestamps();
        $table->integer('stock_qty');
        $table->integer('source_id');
        $table->string('source_type');
        $table->bigInteger('item_id');
        $table->foreign('item_id')->references('id')->on('items');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_transactions');
    }
}
