<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\FavoriteAlbum;
use Illuminate\Http\Request;
use App\Models\Course;

class FavoritesController extends Controller
{
    public function getAlbums(Request $request)
    {
        $userId = $request->input('userId');
        // Get albums with their associated courses count
        $albums = FavoriteAlbum::where('user_id', $userId)->withCount('favorites')->get();

        // Transform the response to include only titles and course count

        return response()->json([
            'albums' => $albums,
        ]);
    }

    public function createAlbum(Request $request){
        $title = $request->input('title');
        $userId = $request->input('userId');

        $album = new FavoriteAlbum();
        $album->title = $title;
        $album->user_id = $userId;
        $album->created_at = now();
        $album->updated_at = now();
        $album->save();

        return response()->json([
            'message' => 'Album created successfully',
            'album' => $album,
        ]);

    }
    public function saveCourse(Request $request){
        $courseId = $request->input('courseId');
        $albumId = $request->input('albumId');

        $favorite = new Favorite();
        $favorite->user_id = 1;
        $favorite->course_id = $courseId;
        $favorite->album_id = $albumId;
        $favorite->created_at = now();
        $favorite->updated_at = now();
        $favorite->save();

        return response()->json([
            'message' => 'Course saved successfully'
        ]);
    }

    public function getSavedCourses(Request $request){
        $albumId = $request->input('albumId');
        $favoriteCourses = Favorite::where('album_id', $albumId)
            ->with(['course.user', 'course.category', 'course.lessons', 'course.enrollments'])
            ->get()
            ->map(function($favorite) {
                $course = $favorite->course;
                return [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'course_tags' => $course->tags,
                    'course_thumbnail' => $course->thumbnail,
                    'author' => $course->user->first_name . ' ' . $course->user->last_name,
                    'category_name' => $course->category->name,
                    'course_price' => $course->price,
                    'number_of_lessons' => $course->lessons->count(),
                    'number_of_enrollments' => $course->enrollments->count(),
                ];
            });

        if($favoriteCourses->isEmpty()){
            return response()->json(['error' => "No saved courses in this album"]);
        }
        return response()->json($favoriteCourses);
    }
}
