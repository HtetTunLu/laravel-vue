<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class TeamsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = 5;
        $start_index = ($request->search * $per_page) - 5;
        $end_index = $request->search * $per_page;
        $teams = Team::all();
        $search_teams = $teams->filter(function ($name, $key) use ($start_index, $end_index) {
            if ($key >= $start_index && $key < $end_index) {
                return $name;
            }
        });
        $result = TeamResource::collection($search_teams);
        $custom = [$teams->count() > $request->search * 5, $teams->count()];
        return $this->sendCustomResponse($result, $custom, 'Teams retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required | unique:accessories',
            'floor' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Not valid inputs', $validator->errors());
        }
        $team = Team::create($input);

        return $this->sendResponse(new TeamResource($team), 'Team created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $team = Team::find($id);

        if (is_null($team)) {
            return $this->sendError('Team not found.');
        }

        return $this->sendResponse(new TeamResource($team), 'Team retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'floor' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Not valid inputs', $validator->errors());
        }
        $team->name = $input['name'];
        $team->floor = $input['floor'];

        $team->save();

        return $this->sendResponse(new TeamResource($team), 'Team updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        $team->delete();

        return $this->sendResponse([], 'Team deleted successfully.');
    }
}
