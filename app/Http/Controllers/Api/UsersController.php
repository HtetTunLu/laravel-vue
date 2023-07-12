<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Http\Resources\UserInfoResource;
use App\Models\User;
use App\Models\Team;
use App\Models\UserInfo;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class UsersController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = 5;
        $start_index = ($request->search * $per_page) - 5;
        $end_index = $request->search * $per_page;
        $users = User::all();
        $users = $users->map(function ($user, $key) {
            $user_info = UserInfo::where('user_id', $user['id'])->first();
            if ($user_info) {
                if ($user_info['avatar']) {
                    $path = public_path() . "/upload/" . $user_info['avatar'];
                    if (!File::exists($path)) {
                        return response()->json(['message' => 'Image not found.'], 404);
                    }
                    $file = (string) File::get($path);
                }
                $user->avatar = $user_info['avatar'] ? base64_encode($file) : null;
                $user->employee_id = $user_info['employee_id'];
                $user->team = $user_info->team->name;
                $user->entry_date = $user_info['entry_date'];
            }
            return $user;
        });
        $search_users = $users->filter(function ($name, $key) use ($start_index, $end_index) {
            if ($key >= $start_index && $key < $end_index) {
                return $name;
            }
        });
        $result = UserInfoResource::collection($search_users);
        $custom = [$users->count() > $request->search * 5, $users->count()];
        return $this->sendCustomResponse($result, $custom, 'Users retrieved successfully.');
    }

    public function csv_download()
    {
        $users = User::all();

        $users = $users->map(function ($user, $key) {
            $user_info = UserInfo::where('user_id', $user['id'])->first();
            $user = [
                'name' => $user->name,
                'email' => $user->email,
                'employee_id' =>  $user_info ? $user_info['employee_id'] : "",
                'team' => $user_info ? $user_info->team->name : "",
                'entry_date' => $user_info ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user_info['entry_date'])
                    ->format('Y-m-d') : ""
            ];
            return $user;
        });
        return $this->sendResponse($users, 'Users send successfully.');
    }

    public function csv_upload(Request $request)
    {
        $file = $request->file;

        $customerArr = $this->csvToArray($file);
        for ($i = 0; $i < count($customerArr); $i++) {
            $select_team = Team::where('name', $customerArr[$i]['team'])->first();
            if (!$select_team) {
                $line_no = $i + 1;
                return "invalid team in lin number $line_no";
            }
            $user = User::firstOrCreate([
                'name' => $customerArr[$i]['name'],
                'email' => $customerArr[$i]['email'],
                'password' => Hash::make("Password")
            ]);
            UserInfo::firstOrCreate([
                'user_id' => $user->id,
                'employee_id' => $customerArr[$i]['employee_id'],
                'team_id' => Team::where('name', $customerArr[$i]['team'])->first()->id,
                'entry_date' => \Carbon\Carbon::parse($customerArr[$i]['entry_date'])->toDateString()
            ]);
            UserRole::firstOrCreate([
                'role_id' => 3,
                'user_id' => $user->id
            ]);
        }

        return $this->sendResponse([], 'Users send successfully.');
    }

    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required | unique:users',
            'email' => 'required | unique:users',
            'employee_id' => 'required',
            'entry_date' => 'required',
            'team_id' => 'required',
            'avatar' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Not valid inputs', $validator->errors());
        }

        $upload_path = public_path('upload');
        $generated_new_name = time() . '.' . $request->avatar->getClientOriginalExtension();
        $request->avatar->move($upload_path, $generated_new_name);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            'password' => Hash::make("Password")
        ]);
        $info = [
            'user_id' => $user['id'],
            'avatar' => $generated_new_name,
            'employee_id' => $request->employee_id,
            'team_id' => $request->team_id,
            'entry_date' => $request->entry_date
        ];

        $userInfo = UserInfo::create($info);
        UserRole::create(['role_id' => 3, 'user_id' => $user['id']]);
        return $this->sendResponse(new UserInfoResource($userInfo), 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }
        $user_info = UserInfo::where('user_id', $user['id'])->first();
        if ($user_info) {
            $path = public_path() . "/upload/" . $user_info['avatar'];
            if (!File::exists($path)) {
                return response()->json(['message' => 'Image not found.'], 404);
            }
            $file = (string) File::get($path);
            $user_data = [
                "name" => $user->name,
                "email" => $user->email,
                "employee_id" => $user_info['employee_id'],
                "team" => $user_info->team->id,
                "entry_date" => $user_info['entry_date'],
                "avatar" => base64_encode($file),
            ];
            return $this->sendResponse($user_data, 'User retrieved successfully.');
        }
        return $this->sendResponse($user, 'User retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required ',
            'email' => 'required ',
            'employee_id' => 'required',
            'entry_date' => 'required',
            'team_id' => 'required',
            'avatar' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Not valid inputs', $validator->errors());
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user_info = UserInfo::where("user_id", $user->id)->first();
        if (!is_string($request->avatar)) {
            $upload_path = public_path('upload');
            $generated_new_name = time() . '.' . $request->avatar->getClientOriginalExtension();
            $request->avatar->move($upload_path, $generated_new_name);
            $user_info->avatar = $generated_new_name;
        }
        $user_info->employee_id = $request->employee_id;
        $user_info->team_id = $request->team_id;
        $user_info->entry_date = $request->entry_date;
        $user->save();

        $user_info->save();

        return $this->sendResponse(new UserInfoResource($user_info), 'User update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->sendResponse([], 'User deleted successfully.');
    }
}
