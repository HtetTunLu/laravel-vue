<?php

use App\Http\Controllers\API\AccessoriesController;
use App\Http\Controllers\API\AccessoryListsController;
use App\Http\Controllers\API\TeamsController;
use App\Http\Controllers\API\UsersController;
use App\Http\Controllers\API\RecordsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('accessories', AccessoriesController::class);
    Route::resource('teams', TeamsController::class);
    Route::resource('users', UsersController::class);
    Route::resource('records', RecordsController::class);
    Route::resource('accessory_lists', AccessoryListsController::class);
    Route::get('get_accessories', [AccessoriesController::class, "get_accessories"]);
    Route::get('get_teams', [TeamsController::class, "get_teams"]);
    Route::get('csv_download', [UsersController::class, "csv_download"]);
    Route::post('csv_upload', [UsersController::class, "csv_upload"]);
    Route::get('accessory_infos', [AccessoriesController::class, "get_infos"]);
    Route::get('overall', [RecordsController::class, 'overall']);
});
