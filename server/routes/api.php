<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['middleware' => 'api', 'prefix' => 'auth'], function($router){
    Route::post('/user/create', [UserController::class, 'register']);
    Route::post('/user/login', [UserController::class, 'login']);
});
