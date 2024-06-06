<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\FavoritesController;
use App\Models\Favorite;
use App\Models\FavoriteAlbum;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\EnrollmentController;



Route::middleware('auth:api')->get('/user', [UserController::class, 'user']);



Route::group(['middleware' => 'api', 'prefix' => 'auth'], function($router){
    Route::post('/user/create', [UserController::class, 'register']);
    Route::post('/user/login', [UserController::class, 'login']);
    Route::post('/user/logout', [UserController::class, 'logout']);
});
Route::get('/course/enrolled', [CourseController::class, 'getEnrolledCourses']);
Route::get('/user/toptutors', [UserController::class, 'getTopTutors']);
Route::get('/course/featured/{limit}', [CourseController::class, 'getFeaturedCourses']);
Route::get('/search/{query}',[SearchController::class, 'search']);
Route::get('/course/details/{courseId}', [CourseController::class, 'courseDetails']);
Route::post('/album/create', [FavoritesController::class, 'createAlbum']);
Route::get('/album/get/{userId}', [FavoritesController::class, 'getAlbums']);
Route::post('/course/album/save', [FavoritesController::class, 'saveCourse']);
Route::get('/album/saved/get/{courseId}', [FavoritesController::class, 'getSavedCourses']);
Route::post('/course/create', [CourseController::class, 'createCourse']);
Route::post('/course/lesson/create', [LessonController::class, 'createLesson']);
Route::get('/categories/get', [CategoryController::class, 'getCategories']);
Route::get('/levels/get', [LevelController::class, 'getLevels']);
Route::get('/course/{courseId}/lesson', [LessonController::class, 'getLessons']);
Route::post('/course/lesson/update/{id}', [LessonController::class,'updateLesson']);
Route::delete('/lesson/delete/{id}', [LessonController::class, 'deleteLesson']);
Route::post('/forgot/password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::get('/users/{userId}/courses', [CourseController::class, 'getCoursesByUser']);
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::delete('course/delete/{id}', [CourseController::class, 'deleteCourse']);
Route::post('course/update/{id}', [CourseController::class, 'updateCourse']);
Route::get('/category/{categoryId}/courses', [CategoryController::class, 'getCoursesByCategory']);
Route::get('/course/{courseId}/lessons', [LessonController::class, 'getLessonsByCourse']);
Route::post('/reviews', [ReviewController::class, 'createReview']);
Route::get('/course/{courseId}/reviews', [ReviewController::class, 'courseReview']);
Route::post('/course/{courseId}/enroll', [EnrollmentController::class, 'enroll']);
