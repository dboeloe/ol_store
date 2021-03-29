<?php

namespace App\Repositories;

use App\Models\OrderDetail;

class OrderDetailRepository
{
  protected $orderDetail;
  protected $item;

  public function __construct(OrderDetail $orderDetail)
  {
    $this->orderDetail = $orderDetail;
  }

  public function create($attributes, $userOrder)
  {
    $orderDetail = new OrderDetail([
      'order_price' => 0.0,
      'order_qty' => $attributes['stock_qty'],
      'item_id' => $attributes['id']
      ]);
    $userOrder->orderDetails()->save($orderDetail);
  }
}
