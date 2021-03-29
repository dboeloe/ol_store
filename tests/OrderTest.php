<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Item;
use App\Models\StockTransaction;

class OrderTest extends TestCase
{
  use DatabaseTransactions;
  /**
   * A basic test example.
   *
   * @return void
   */
  public function testOrderFlow()
  {
    $user = factory('App\Models\User')->create();

    $header = [
      'Authorization' =>  $user->api_key
    ];

    # Add items
    $items = [
      ['name' => 'Test Item 1', 'stock_qty' => 10],
      ['name' => 'Test Item 2 No stock'],
    ];

    foreach ($items as $item) {
      $this->json('POST', '/api/item', $item, $header)
        ->seeJson([
          "message" => 'item creation success'
        ]);
      if (array_key_exists('stock_qty', $item)) {
        $stock = $item['stock_qty'];
      } else {
        $stock = 0;
      }
      $this->seeInDatabase('items', ['user_id' => $user->id, 'name' => $item['name'], 'stock_qty' => $stock]);
    }

    # Add stock
    $items = Item::where('user_id', $user->id)->get();
    foreach ($items as $item) {

      if ($item->stock_qty == 0){
        $isCheckOut = true;
        $message = 'Failed to update stock';
      } else {
        $isCheckOut = false;
        $message = 'Stock updated';
      }
      $isCheckOut = $item->stock_qty == 0 ? true : false;

      $body = [
        'stock_qty' => 100,
        'check_out' => $isCheckOut
      ];

      $url = '/api/item/'.$item->id.'/stock';
      $this->json('POST', $url, $body, $header)
        ->seeJson([
          "message" => $message
        ]);
    }

    #add Order
  }
}
