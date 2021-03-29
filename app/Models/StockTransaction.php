<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;


class StockTransaction extends Model implements Authenticatable
{
  //
  use AuthenticableTrait;

  protected $fillable = ['item_id', 'source_id','source_type', 'stock_qty'];

  /**
   * Get the item that owns the stock transaction.
   */
  public function item()
  {
    return $this->belongsTo(Item::class);
  }

}
