<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\FavoriteAlbum;
use Illuminate\Http\Request;
use App\Models\Course;

class FavoritesController extends Controller
{

    /**
 * @OA\Get(
 *     path="/api/album/get/{userId}",
 *     summary="Retrieves all favorite albums for a specific user",
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         description="The user ID to retrieve favorite albums for",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful retrieval of favorite albums",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="albums",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", description="ID of the album"),
 *                     @OA\Property(property="user_id", type="integer", description="ID of the user who owns the album"),
 *                     @OA\Property(property="title", type="string", description="Title of the album"),
 *                     @OA\Property(property="favorites_count", type="integer", description="Number of favorites in the album")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="No albums found for the user"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error"
 *     )
 * )
 */


    public function getAlbums(Request $request, $userId)
    {
        // Get albums with their associated courses count
        $albums = FavoriteAlbum::where('user_id', $userId)->withCount('favorites')->get();

        // Transform the response to include only titles and course count

        return response()->json([
            'albums' => $albums,
        ]);
    }

    /**
 * @OA\Post(
 *     path="/api/album/create",
 *     summary="Creates a new favorite album",
 *     @OA\RequestBody(
 *         description="Data needed to create a new album",
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "userId"},
 *             @OA\Property(property="title", type="string", description="Title of the new album"),
 *             @OA\Property(property="userId", type="integer", description="User ID of the album owner")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Album created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Success message"),
 *             @OA\Property(property="album", type="object", 
 *                 @OA\Property(property="id", type="integer", description="ID of the newly created album"),
 *                 @OA\Property(property="title", type="string", description="Title of the album"),
 *                 @OA\Property(property="user_id", type="integer", description="User ID of the album owner"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", description="Time when the album was created"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Time when the album was last updated")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Invalid input, missing title or userId"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error"
 *     )
 * )
 */


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

    /**
 * @OA\Post(
 *     path="/api/courses/save",
 *     summary="Saves a course to a user's favorite album",
 *     @OA\RequestBody(
 *         description="Data needed to save a course to an album",
 *         required=true,
 *         @OA\JsonContent(
 *             required={"courseId", "albumId"},
 *             @OA\Property(property="courseId", type="integer", description="ID of the course to be saved"),
 *             @OA\Property(property="albumId", type="integer", description="ID of the album where the course is to be saved")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Course saved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Success message indicating the course has been saved")
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Invalid input data"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error"
 *     )
 * )
 */


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

    /**
 * @OA\Get(
 *     path="/api/album/saved/get/{albumId}",
 *     summary="Retrieves all courses saved in a specified favorite album",
 *     @OA\Parameter(
 *         name="albumId",
 *         in="path",
 *         required=true,
 *         description="The ID of the favorite album to retrieve courses from",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful retrieval of saved courses",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="course_id", type="integer", description="ID of the course"),
 *                 @OA\Property(property="course_title", type="string", description="Title of the course"),
 *                 @OA\Property(property="course_tags", type="string", description="Tags associated with the course"),
 *                 @OA\Property(property="course_thumbnail", type="string", description="URL of the course thumbnail image"),
 *                 @OA\Property(property="author", type="string", description="Name of the course author"),
 *                 @OA\Property(property="category_name", type="string", description="Name of the category to which the course belongs"),
 *                 @OA\Property(property="course_price", type="number", format="float", description="Price of the course"),
 *                 @OA\Property(property="number_of_lessons", type="integer", description="Total number of lessons in the course"),
 *                 @OA\Property(property="number_of_enrollments", type="integer", description="Total number of enrollments in the course")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="No saved courses in this album",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", description="Error message indicating no courses found in the album")
 *         )
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error"
 *     )
 * )
 */


    public function getSavedCourses(Request $request, $albumId){
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
