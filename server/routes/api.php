<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', [UserController::class, 'user']);


Route::group(['middleware' => 'api', 'prefix' => 'auth'], function($router){
    Route::post('/user/create', [UserController::class, 'register']);
    Route::post('/user/login', [UserController::class, 'login']);
    Route::post('/user/logout', [UserController::class, 'logout']);
});
