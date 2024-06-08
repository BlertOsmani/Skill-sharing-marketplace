<?php

namespace App\Http\Controllers;


use App\Models\Course;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    /**
 * @OA\Get(
 *     path="/api/category/{categoryId}/courses",
 *     summary="Retrieves all courses within a specific category",
 *     @OA\Parameter(
 *         name="categoryId",
 *         in="path",
 *         required=true,
 *         description="The ID of the category for which courses are to be retrieved",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful retrieval of courses",
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
 *         description="No courses found for the category"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error"
 *     )
 * )
 */


    public function getCoursesByCategory(Request $request, $categoryId)
    {
        $courses = Course::with(['category', 'user', 'lessons', 'enrollments'])
            ->whereHas('category', function($q) use ($categoryId) {
                $q->where('id', $categoryId);
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
                    'course_price' => $course->price,
                    'number_of_lessons' => $course->lessons->count(),
                    'number_of_enrollments' => $course->enrollments->count(),
                ];
            });

        return response()->json($courses);
    }

    /**
 * @OA\Get(
 *     path="/api/categories/get",
 *     summary="Retrieves all course categories",
 *     @OA\Response(
 *         response=200,
 *         description="Successful retrieval of all categories",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", description="ID of the category"),
 *                 @OA\Property(property="name", type="string", description="Name of the category"),
 *                 @OA\Property(property="description", type="string", description="Description of the category"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", description="Creation date of the category"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Last update date of the category")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error"
 *     )
 * )
 */


    public function getCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
}
