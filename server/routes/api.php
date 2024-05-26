<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SearchController;
use App\Models\Favorite;
use App\Models\FavoriteAlbum;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/user/create', [UserController::class, 'register']);

Route::get('/course/enrolled', [CourseController::class, 'getEnrolledCourses']);

Route::get('/user/toptutors', [UserController::class, 'getTopTutors']);
Route::get('/user/toptutors', [UserController::class, 'getTopTutors']);

Route::get('/search',[SearchController::class, 'search']);

Route::get('/course/featured', [CourseController::class, 'getFeaturedCourses']);

Route::get('/course/details', [CourseController::class, 'courseDetails']);
