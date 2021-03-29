<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerOrderStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::Statement("
      CREATE OR REPLACE FUNCTION adjust_stock()
      RETURNS TRIGGER AS
      $$
         DECLARE
          qty int;
         BEGIN
          SELECT stock_qty INTO qty FROM items where id = NEW.item_id;
          IF qty + NEW.stock_qty >= 0 THEN
            UPDATE items SET stock_qty = stock_qty + COALESCE(NEW.stock_qty, 0) where id = NEW.item_id;
            RETURN NEW;
          ELSE
            RAISE EXCEPTION 'Stock unavailable';
          END IF;
         END;
      $$
      LANGUAGE 'plpgsql';"
    );

      DB::Statement("
        CREATE TRIGGER update_item_stock_on_insert
        AFTER INSERT ON stock_transactions
        FOR EACH ROW
        EXECUTE PROCEDURE adjust_stock();"
      );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::Statement("DROP TRIGGER IF EXISTS update_item_stock_on_insert ON stock_transactions;");
      DB::Statement("DROP TRIGGER IF EXISTS update_item_stock_on_update ON stock_transactions;");
    }
}
