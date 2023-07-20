<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\RecordResource;
use App\Models\Accessory;
use App\Models\AccessoryList;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SendPushNotification;

class RecordsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = 5;
        $start_index = ($request->search * $per_page) - 5;
        $end_index = $request->search * $per_page;
        $records = Record::all();
        $search_users = $records->filter(function ($name, $key) use ($start_index, $end_index) {
            if ($key >= $start_index && $key < $end_index) {
                return $name;
            }
        });
        $result = RecordResource::collection($search_users);
        $custom = [$records->count() > $request->search * 5, $records->count()];
        return $this->sendCustomResponse($result, $custom, 'Records retrieved successfully.');
    }

    public function overall()
    {
        $overall = [];
        $floors = [1, 2, 4];
        foreach ($floors as $floor) {
            $accessories = Accessory::all();
            foreach ($accessories as $accessory) {
                $lists = AccessoryList::where('floor', $floor)->where('accessory_id', $accessory->id)->get();
                $total = collect($lists)
                    ->reduce(function ($carry, $item) {
                        return $carry + $item["quantity"];
                    }, 0);

                $records = Record::where('floor', $floor)->where('accessory_id', $accessory->id)->get();
                $used = collect($records)
                    ->reduce(function ($carry, $item) {
                        return $carry + $item["count"];
                    }, 0);
                $hahaha = new \StdClass();
                $hahaha->accessory = $accessory->name;
                $hahaha->floor = $floor;
                $hahaha->total = $total;
                $hahaha->used = $used;
                array_push($overall, $hahaha);
            }
        }
        return $this->sendResponse($overall, 'Record created successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'accessory_id' => 'required',
            'count' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Not valid inputs', $validator->errors());
        }
        $lists = AccessoryList::where('accessory_id', $request->accessory_id)
            ->where('floor', Auth::user()->user_info->team->floor)->get();
        $records = Record::where('accessory_id', $request->accessory_id)
            ->where('floor', Auth::user()->user_info->team->floor)->get();
        $total = 0;
        $used = 0;
        foreach ($lists as $list) {
            $total = $total + $list->quantity;
        };
        foreach ($records as $record) {
            $used = $used + $record->count;
        };
        if ($total - $used < $request->count) {
            return $this->sendError("Can't use this amout", $validator->errors());
        }
        $input['user_id'] = Auth::user()->id;
        $input['floor'] = Auth::user()->user_info->team->floor;
        $record = Record::create($input);

        return $this->sendResponse(new RecordResource($record), 'Record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Record $record)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Record $record)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Record $record)
    {
        //
    }
}
