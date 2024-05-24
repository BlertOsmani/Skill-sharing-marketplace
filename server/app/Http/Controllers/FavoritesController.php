<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\FavoriteAlbum;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function getAlbums(Request $request)
    {
        // Get albums with their associated courses count
        $albums = FavoriteAlbum::withCount('favorites')->get();

        // Transform the response to include only titles and course count

        return response()->json([
            'albums' => $albums,
        ]);
    }

    public function createAlbum(Request $request){
        $title = $request->input('title');
        $userId = 1;

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

    }
}
