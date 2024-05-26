<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FavoritesController;
use App\Models\Favorite;
use App\Models\FavoriteAlbum;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;

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
Route::post('/album/create', [FavoritesController::class, 'createAlbum']);

Route::get('/album/get', [FavoritesController::class, 'getAlbums']);

Route::post('/course/save', [FavoritesController::class, 'saveCourse']);

Route::get('/album/saved/get', [FavoritesController::class, 'getSavedCourses']);

Route::post('/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
