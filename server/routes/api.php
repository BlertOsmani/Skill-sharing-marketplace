<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/user/create', [UserController::class, 'register']);

Route::get('/course/enrolled', [CourseController::class, 'getEnrolledCourses']);

Route::get('/user/toptutors', [UserController::class, 'getTopTutors']);