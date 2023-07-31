<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\RecordResource;
use App\Models\Accessory;
use App\Models\AccessoryList;
use App\Models\Record;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SendPushNotification;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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
                $obj = new \StdClass();
                $obj->accessory = $accessory->name;
                $obj->floor = $floor;
                $obj->total = $total;
                $obj->used = $used;
                array_push($overall, $obj);
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
        if ($total - $used - $request->count <= $lists->last()->remind_limit) {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $FcmToken = User::whereNotNull('fcm_token')->pluck('fcm_token')->all();

            $serverKey = env('FIREBASE_SERVER_KEY');
            $access = Accessory::find($request->accessory_id);
            $flr = Auth::user()->user_info->team->floor;
            $data = [
                "registration_ids" => $FcmToken,
                "notification" => [
                    "title" => "Accessory Remind Lmiit",
                    "body" => "$flr floor mhr $access->name remind limit kyaw nay p",
                    "link" => "http://localhost:8000"
                ]
            ];
            $encodedData = json_encode($data);

            $headers = [
                'Authorization:key=' . $serverKey,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            // Close connection
            curl_close($ch);
            $msg = "$flr floor mhr $access->name remind limit kyaw nay p";
        }
        $input['user_id'] = Auth::user()->id;
        $input['floor'] = Auth::user()->user_info->team->floor;
        $record = Record::create($input);
        $record['msg'] = isset($msg) ? $msg : null;
        return $this->sendResponse(new RecordResource($record), 'Record created successfully.');
    }

    public function skypeMsg(Request $request)
    {
        $process = new Process(
            ['python', public_path() . '/skp.py', $request->msg],
            null,
            ['SYSTEMROOT' => getenv('SYSTEMROOT'), 'PATH' => getenv("PATH")]
        );
        $process->run();
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
