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
Route::post('/user/create', [UserController::class, 'register']);
Route::get('/course/enrolled', [CourseController::class, 'getEnrolledCourses']);
Route::get('/user/toptutors', [UserController::class, 'getTopTutors']);
Route::get('/search',[SearchController::class, 'search']);
Route::get('/course/featured', [CourseController::class, 'getFeaturedCourses']);

Route::get('/course/details', [CourseController::class, 'courseDetails']);
Route::post('/album/create', [FavoritesController::class, 'createAlbum']);
Route::get('/album/get', [FavoritesController::class, 'getAlbums']);
Route::post('/course/save', [FavoritesController::class, 'saveCourse']);
Route::get('/album/saved/get', [FavoritesController::class, 'getSavedCourses']);
Route::post('/course/create', [CourseController::class, 'createCourse']);
Route::post('/course/lesson/create', [LessonController::class, 'createLesson']);
Route::get('/categories/get', [CategoryController::class, 'getCategories']);
Route::get('/levels/get', [LevelController::class, 'getLevels']);
Route::get('/course/lesson/get', [LessonController::class, 'getLessons']);
Route::post('/course/lesson/update/{id}', [LessonController::class,'updateLesson']);
Route::delete('/lesson/delete/{id}', [LessonController::class, 'deleteLesson']);
Route::post('/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::get('/category', [CategoryController::class, 'getCoursesByCategory']);
Route::get('courses/{courseId}/lessons', [LessonController::class, 'getLessonsByCourse']);
Route::post('/reviews', [ReviewController::class, 'createReview']);
Route::get('/reviews/{courseId}', [ReviewController::class, 'courseReview']);
Route::post('/course/{courseId}/enroll', [EnrollmentController::class, 'enroll']);
