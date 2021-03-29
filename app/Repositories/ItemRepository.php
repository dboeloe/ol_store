<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\StockTransaction;
use Auth;

class ItemRepository
{
  protected $item;

  public function __construct(Item $item)
  {
    $this->item = $item;
  }

  public function create($attributes)
  {
    $item = new Item($attributes);
    return Auth::user()->items()->save($item);
  }

  public function checkInOrOut($attributes, $sourceObject, $sourceType, $checkOut = true)
  {

    $qty = $attributes['stock_qty'];
    if ($checkOut) {
      $qty = -1 * $qty;
    }
    $stockTrans = new StockTransaction([
      'stock_qty' => $qty,
      'source_id' => $sourceObject->id,
      'source_type' => $sourceType
      ]);

    $this->item->find($attributes['id'])->stockTransactions()->save($stockTrans);
  }

  public function find($itemId)
  {
    return Auth::user()->items()->find($itemId);
  }
}
