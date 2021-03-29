<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;


class User extends Model implements Authenticatable
{
  //
  use AuthenticableTrait;
  use HasFactory;
  protected $fillable = ['name','email','password'];
  protected $hidden = ['password'];

  /**
  * Get the items for the user.
  */
  public function items()
  {
    return $this->hasMany(Item::class);
  }

  /**
  * Get the order for the user.
  */
  public function orders()
  {
    return $this->hasMany(Order::class);
  }


}
