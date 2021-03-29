<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;


class Order extends Model implements Authenticatable
{
  //
  use AuthenticableTrait;

  protected $fillable = ['user_id','order_status'];

  /**
   * Get the user that owns the order.
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
  * Get the order details for the order.
  */
  public function orderDetails()
  {
    return $this->hasMany(OrderDetail::class);
  }

}
