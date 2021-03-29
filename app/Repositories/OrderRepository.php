<?php

namespace App\Repositories;

use App\Models\Order;
use Auth;

class OrderRepository
{
  protected $order;

  public function __construct(Order $order)
  {
    $this->order = $order;
  }

  public function create()
  {
    $order = new Order([
      'order_status' => 'open'
    ]);
    return Auth::user()->orders()->save($order);
  }
  public function updateStatus($message, $userOrder)
  {
    $userOrder->order_status = $message;
    $userOrder->save();
  }

  public function find($id)
  {
    return Auth::user()->orders()->find($id);
  }
}
