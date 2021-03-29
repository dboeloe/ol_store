<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\OrderRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\ItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderService
{
	public function __construct(OrderRepository $order, OrderDetailRepository $orderDetail, ItemRepository $item)
	{
		$this->order = $order;
    $this->orderDetail = $orderDetail;
    $this->item = $item;
	}

  public function create(Request $request)
	{
    $validator = Validator::make($request->all(), [
        'item' => 'required|array',
        'item.*.id' => 'required|numeric|exists:items,id',
        'item.*.stock_qty' => 'required|numeric|min:1',
      ]);

    if ($validator->fails()) {
      return [
        'status_code' => 422,
        'message' => $validator->errors()
      ];
    }

    $response = [
      'message' => 'order created',
      'status_code' => 201
    ];

    $userOrder = $this->order->create();

    if ($userOrder) {
      DB::beginTransaction();
      $status_code = 201;
      $status = 'success';
      try {
        foreach ($request->item as $key => $value) {
          $this->item->checkInOrOut($value, $userOrder, 'order');
          $this->orderDetail->create($value, $userOrder);
        }

        DB::commit();
      } catch (\Throwable $th) {
        DB::rollback();
        $response = [
          'message' => 'order creation failed',
          'status_code' => 422
        ];
        $this->order->updateStatus('cancelled by system', $userOrder);
      }
    } else {
      $status_code = 422;
      $status = 'cannot create order';
    }
    return $response;
	}

  public function cancel(Request $request, $orderId)
  {
    $response = [
      'message' => 'order cancellation success',
      'status_code' => 201
    ];

    $userOrder = $this->order->find($orderId);

    if ($userOrder && $userOrder->order_status == 'open') {
      DB::beginTransaction();
      try {
        foreach ($userOrder->orderDetails as $detail) {
          $attributes = ['id' => $detail->item_id, 'stock_qty' => $detail->order_qty];
          $this->item->checkInOrOut($attributes, $userOrder, 'order cancellation', false);
        }

        $this->order->updateStatus('cancelled by user', $userOrder);
        DB::commit();
      } catch (\Throwable $th) {
        dd($th);
        DB::rollback();
        $response = [
          'message' => 'order cancellation failed',
          'status_code' => 422
        ];
      }

    } else {
      $response = [
        'message' => 'order can not be cancelled',
        'status_code' => 422
      ];
    }
    return $response;
  }
}
