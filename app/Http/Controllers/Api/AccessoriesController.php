<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\AccessoryResource;
use App\Models\Accessory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

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
        return $this->sendResponse(AccessoryResource::collection($accessories), 'Post retrieved successfully.');
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

        return $this->sendResponse(new AccessoryResource($post), 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Accessory::find($id);

        if (is_null($post)) {
            return $this->sendError('Post not found.');
        }

        return $this->sendResponse(new AccessoryResource($post), 'Post retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Accessory $post)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $post->title = $input['title'];
        $post->body = $input['body'];
        $post->save();

        return $this->sendResponse(new AccessoryResource($post), 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Accessory $post)
    {
        $post->delete();

        return $this->sendResponse([], 'Post deleted successfully.');
    }
}
