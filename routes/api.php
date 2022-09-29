<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LoginController;



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [LoginController::class, 'authenticate']);
Route::post('register', [LoginController::class, 'register']);

//Route::get('/list-prod', [ProductController::class, 'index']);

Route::group(['middleware' => ['cors', 'json.response']], function () {
        Route::middleware(['auth:api'])->group(function () {
        Route::post('logout', [LoginController::class, 'logout']);

         Route::middleware(['auth.admin'])->group(function () {

        Route::post('/create-prod', [ProductController::class, 'create']);
        Route::post('/update-prod', [ProductController::class, 'update']);
        Route::post('/delete-prod',  [ProductController::class, 'delete']);
        Route::get('/list-prod', [ProductController::class, 'index']);
    }); 
});
    // ...
});



