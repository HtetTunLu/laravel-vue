<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\AccessoryListResource;
use App\Models\AccessoryList;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AccessoryListsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = 5;
        $start_index = ($request->search * $per_page) - 5;
        $end_index = $request->search * $per_page;
        $lists = AccessoryList::all();
        $search_lists = $lists->filter(function ($name, $key) use ($start_index, $end_index) {
            if ($key >= $start_index && $key < $end_index) {
                return $name;
            }
        });
        $result = AccessoryListResource::collection($search_lists);
        $custom = [$lists->count() > $request->search * 5, $lists->count()];
        return $this->sendCustomResponse($result, $custom, 'Lists retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'accessory_id' => 'required',
            'floor' => 'required',
            'quantity' => 'required',
            'remind_limit' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Not valid inputs', $validator->errors());
        }
        $list = AccessoryList::create($input);

        return $this->sendResponse(new AccessoryListResource($list), 'Accessory List created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $list = AccessoryList::find($id);

        if (is_null($list)) {
            return $this->sendError('Accessory List not found.');
        }
        return $this->sendResponse(new AccessoryListResource($list), 'Accessory lists retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccessoryList $accessoryList)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'accessory_id' => 'required',
            'floor' => 'required',
            'quantity' => 'required',
            'remind_limit' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Not valid inputs', $validator->errors());
        }
        $accessoryList->accessory_id = $request->accessory_id;
        $accessoryList->floor = $request->floor;
        $accessoryList->quantity = $request->quantity;
        $accessoryList->remind_limit = $request->remind_limit;
        $accessoryList->save();

        return $this->sendResponse(new AccessoryListResource($accessoryList), 'Accessory list updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccessoryList $accessoryList)
    {
        $accessoryList->delete();

        return $this->sendResponse([], 'Accessory list deleted successfully.');
    }
}
