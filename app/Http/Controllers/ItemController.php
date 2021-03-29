<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\StockTransaction;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Services\ItemService;

class ItemController extends Controller
{

  protected $itemService;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(ItemService $itemService)
  {
    $this->itemService = $itemService;
    $this->middleware('auth');
  }

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $item = $this->itemService->create($request);
    return response()->json(['message' => $item['message']], $item['status_code']);
  }

  public function updateStock(Request $request, $id)
  {
    $item = $this->itemService->updateStock($request, $id);
    return response()->json(['message' => $item['message']], $item['status_code']);
  }

}
