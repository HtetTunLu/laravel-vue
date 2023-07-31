<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\AccessoryResource;
use App\Models\Accessory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class AccessoriesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accessories = Accessory::all();
        foreach ($accessories as $accessory) {
            $path = public_path() . "/upload/" . $accessory['image'];
            if (!File::exists($path)) {
                return response()->json(['message' => 'Image not found.'], 404);
            }
            $file = (string) File::get($path);
            $accessory->image = base64_encode($file);
        }
        return $this->sendResponse(AccessoryResource::collection($accessories), 'Accessories retrieved successfully.');
    }

    public function get_accessories()
    {
        $accessories = Accessory::all();
        return $this->sendResponse($accessories, 'Accessories retrieved successfully.');
    }

    public function get_infos()
    {
        $team = Auth::user()->user_info->team;
        $accessories = Accessory::all();
        foreach ($accessories as $accessory) {
            $path = public_path() . "/upload/" . $accessory['image'];
            if (!File::exists($path)) {
                return response()->json(['message' => 'Image not found.'], 404);
            }
            $file = (string) File::get($path);
            $accessory->image = base64_encode($file);
            $total = 0;
            $used = 0;
            $lists = $accessory->accessory_lists->where("floor", $team->floor);
            foreach ($lists as $list) {
                $total = $total + $list->quantity;
            };
            foreach ($accessory->records->where('floor', Auth::user()->user_info->team->floor) as $record) {
                $used = $used + $record->count;
            };
            $accessory->total = $total;
            $accessory->used = $used;
            $accessory->team = $team;
        }
        return $this->sendResponse($accessories, 'Accessories retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required | unique:accessories',
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Not valid inputs', $validator->errors());
        }

        $upload_path = public_path('upload');
        $generated_new_name = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->move($upload_path, $generated_new_name);

        $input['image'] = $generated_new_name;
        $post = Accessory::create($input);

        return $this->sendResponse(new AccessoryResource($post), 'Accessory created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $accessory = Accessory::find($id);

        if (is_null($accessory)) {
            return $this->sendError('Accessory not found.');
        }
        $path = public_path() . "/upload/" . $accessory['image'];
        if (!File::exists($path)) {
            return response()->json(['message' => 'Image not found.'], 404);
        }
        $file = (string) File::get($path);
        $accessory->image = base64_encode($file);

        return $this->sendResponse(new AccessoryResource($accessory), 'Accessory retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Accessory $accessory)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Not valid inputs', $validator->errors());
        }
        if (!is_string($request->image)) {
            $upload_path = public_path('upload');
            $generated_new_name = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move($upload_path, $generated_new_name);
            $accessory->image = $generated_new_name;
        }
        $accessory->name = $input['name'];

        $accessory->save();

        return $this->sendResponse(new AccessoryResource($accessory), 'Accessory updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Accessory $accessory)
    {
        $accessory->delete();

        return $this->sendResponse([], 'Accessory deleted successfully.');
    }
}
