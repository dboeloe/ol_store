<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\OrderRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\ItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;

class ItemService
{
	public function __construct(ItemRepository $item)
	{
    $this->item = $item;
	}

  public function create(Request $request)
	{

    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'stock_qty' => 'nullable|numeric|min:1',
      'tag_ids' => 'nullable|array'
      ]);

    if ($validator->fails()) {
      return [
        'status_code' => 422,
        'message' => $validator->errors()
      ];
    }

    $response = [
      'message' => 'item creation success',
      'status_code' => 201
    ];

    $attributes = [
      'name' => $request->name,
      'tag_ids' => $request->tag_ids
    ];
    $userItem = $this->item->create($attributes);

    if ($userItem) {
      if ($request->filled('stock_qty')) {
        $attributes = ['id' => $userItem->id, 'stock_qty' => $request->stock_qty];
        $this->item->checkInOrOut($attributes, Auth::user(), 'owner', false);
      }
    } else {
      $response = [
        'message' => 'item creation failed',
        'status_code' => 422
      ];
    }

    return $response;
	}

  public function updateStock(Request $request, $itemId)
  {
    $validator = Validator::make($request->all(), [
      'stock_qty' => 'required|numeric',
      'check_out' => 'required|boolean'
    ]);

    if ($validator->fails()) {
      return [
        'status_code' => 422,
        'message' => $validator->errors()
      ];
    }

    $response = [
      'message' => 'Stock updated',
      'status_code' => 201
    ];

    $userItem = $this->item->find($itemId);

    if ($userItem) {
      DB::beginTransaction();
      try {
        $attributes = ['id' => $userItem->id, 'stock_qty' => $request->stock_qty];
        $this->item->checkInOrOut($attributes, Auth::user(), 'owner', $request->check_out);
        DB::commit();
      } catch (\Exception $e) {
        DB::rollback();
        $response = [
          'message' => 'Failed to update stock',
          'status_code' => 422
        ];
      }
    } else {
      $response = [
        'message' => 'Cannot update this item stock',
        'status_code' => 422
      ];
    }
    return $response;
  }
}
