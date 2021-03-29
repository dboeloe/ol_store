<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Item;
use App\Models\StockTransaction;
use Auth;

use App\Services\OrderService;

class OrderController extends Controller
{

  protected $orderService;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(OrderService $orderService)
  {
    $this->orderService = $orderService;
    $this->middleware('auth');

  }

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $order = $this->orderService->create($request);
    return response()->json(['message' => $order['message']], $order['status_code']);
  }

    /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function cancel(Request $request, $id)
  {
    $order = $this->orderService->cancel($request, $id);
    return response()->json(['message' => $order['message']], $order['status_code']);
  }
}
