<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FindMatchController;
use App\Http\Controllers\MatchesController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/user/register', [UserController::class, 'register']);
Route::post('/auth/user/login', [UserController::class, 'login']);
Route::put('/auth/user/profile', [UserController::class, 'profile']);

Route::post('/auth/admin/register', [AdminController::class, 'register']);
Route::post('/auth/admin/login', [AdminController::class, 'login']);

Route::post('/user/match', [MatchesController::class, 'save_match_response']);
Route::get('/user/match', [FindMatchController::class, 'find_match']);

Route::post('/user/message', [MessageController::class, 'send_message']);
Route::get('/user/chats', [MessageController::class, 'get_chats']);
Route::get('/user/convo', [MessageController::class, 'get_conversation']);

Route::get('/user/visibility', [UserController::class, 'user_hide_unhide_info']);

Route::get('/admin/ban', [AdminController::class, 'admin_ban_user']);
Route::get('/admin/users', [AdminController::class, 'admin_get_users']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
