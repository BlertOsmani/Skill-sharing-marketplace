<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{

    /**
 * @OA\Get(
 *     path="/api/search/{query}",
 *     summary="Searches for users and courses based on a query string",
 *     @OA\Parameter(
 *         name="query",
 *         in="path",
 *         required=true,
 *         description="The search query used to find users and courses",
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Search results for users and courses",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="users",
 *                 type="array",
 *                 description="List of users matching the search query",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", description="User ID"),
 *                     @OA\Property(property="name", type="string", description="User's name")
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="courses",
 *                 type="array",
 *                 description="List of courses matching the search query",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", description="Course ID"),
 *                     @OA\Property(property="title", type="string", description="Course title")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", description="Error message detailing an internal error")
 *         )
 *     )
 * )
 */


    public function search(Request $request, $query){
        $searchUsers = $this->searchUsers($query);
        $searchCourses = $this->searchCourses($query);

        return response()->json(
            [
                'users' => $searchUsers,
                'courses' => $searchCourses
            ]
        );
    }

    public function searchUsers($query){
        return User::select('id', 'first_name', 'last_name', 'username', 'profile_picture')
        ->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"])
        ->orWhere('username', 'LIKE', "%{$query}%")
        ->get();
    }
    public function searchCourses($query)
    {
        return Course::with(['user', 'category', 'lessons', 'enrollments'])
        ->where(function($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%")
              ->orWhere('tags', 'LIKE', "%{$query}%");
        })
        ->orWhereHas('category', function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%");
        })
        ->orWhereHas('level', function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%");
        })
        ->orWhereHas('lessons', function($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%");
        })
        ->orWhereHas('user', function($q) use ($query){
            $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"])
                ->orWhere('username', 'LIKE', "%{$query}%");
        })
        ->get()
        ->map(function($course) {
            return [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'course_tags' => $course->tags,
                'course_thumbnail' => $course->thumbnail,
                'author' => $course->user->first_name . ' ' . $course->user->last_name,
                'category_name' => $course->category->name,
                'number_of_lessons' => $course->lessons->count(),
                'number_of_enrollments' => $course->enrollments->count(),
            ];
        });
    }
    
}
