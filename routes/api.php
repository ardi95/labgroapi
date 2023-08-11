<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AppManagement\UserController;
use App\Http\Controllers\AppManagement\RoleController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('/login',[AuthController::class, 'login']);

Route::get('/token-notfound',[AuthController::class, 'token_notfound'])->name('token-notfound');

Route::middleware(['auth:api'])->group(function () {
    Route::get('/detail-user',[AuthController::class, 'detail_user']);
    Route::post('/logout',[AuthController::class, 'logout']);

    //HOME
    Route::get('/home', [HomeController::class, 'index']);

    Route::prefix('app-management')->group(function () {
        Route::resource('user', UserController::class);

        Route::get('/role/temp', [RoleController::class, 'temp']);
    });
});
