<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LevelController;
use App\Models\Favorite;
use App\Models\FavoriteAlbum;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/user/create', [UserController::class, 'register']);

Route::get('/course/enrolled', [CourseController::class, 'getEnrolledCourses']);

Route::get('/user/toptutors', [UserController::class, 'getTopTutors']);

Route::get('/search',[SearchController::class, 'search']);

Route::post('/course/create', [CourseController::class, 'createCourse']);

Route::post('/course/lesson/create', [LessonController::class, 'createLesson']);
Route::get('/categories/get', [CategoryController::class, 'getCategories']);
Route::get('/levels/get', [LevelController::class, 'getLevels']);
Route::get('/course/lesson/get', [LessonController::class, 'getLessons']);
Route::post('/course/lesson/update/{id}', [LessonController::class,'updateLesson']);
Route::delete('/lesson/delete/{id}', [LessonController::class, 'deleteLesson']);

