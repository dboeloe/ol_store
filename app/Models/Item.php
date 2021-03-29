<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;


class Item extends Model implements Authenticatable
{
  //
  use AuthenticableTrait;

  protected $fillable = ['name','user_id','category_id', 'stock_qty'];

  /**
   * Get the user that owns the item.
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
  * Get the stock transactions.
  */
  public function stockTransactions()
  {
    return $this->hasMany(StockTransaction::class);
  }

}
