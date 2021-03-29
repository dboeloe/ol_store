<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;


class OrderDetail extends Model implements Authenticatable
{
  //
  use AuthenticableTrait;

  protected $fillable = ['order_id', 'order_price', 'order_qty', 'item_id'];

  /**
   * Get the order from order detail.
   */
  public function order()
  {
    return $this->belongsTo(Order::class);
  }

}
