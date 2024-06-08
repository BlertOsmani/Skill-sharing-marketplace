<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Course;

class ReviewController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/reviews",
 *     summary="Creates a new review for a course",
 *     @OA\RequestBody(
 *         description="Data needed to create a new review",
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "course_id", "rating", "comment"},
 *             @OA\Property(property="user_id", type="integer", description="ID of the user submitting the review"),
 *             @OA\Property(property="course_id", type="integer", description="ID of the course being reviewed"),
 *             @OA\Property(property="rating", type="integer", description="Rating given to the course (1 to 5)"),
 *             @OA\Property(property="comment", type="string", description="Textual comment of the review, max 200 characters")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review submitted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Success message indicating the review was successfully submitted")
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", description="Error message detailing the validation error")
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


    public function createReview(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:200'
        ]);

        $review = new Review;
        $review->user_id = $request->user_id;
        $review->course_id = $request->course_id;
        $review->rating = $request->rating;
        $review->review_text = $request->comment;
        $review->save();

        return response()->json(['message' => 'Review submitted successfully'], 200);
    }


    /**
 * @OA\Get(
 *     path="/api/course/{courseId}/reviews",
 *     summary="Retrieves a course along with its reviews",
 *     @OA\Parameter(
 *         name="courseId",
 *         in="path",
 *         required=true,
 *         description="The ID of the course to retrieve details for",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detailed information about the course including reviews",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="course_id", type="integer", description="ID of the course"),
 *             @OA\Property(property="author", type="string", description="Name of the course author"),
 *             @OA\Property(
 *                 property="reviews",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="user", type="string", description="Name of the user who wrote the review"),
 *                     @OA\Property(property="review_text", type="string", description="Text of the review"),
 *                     @OA\Property(property="rating", type="integer", description="Rating given by the user"),
 *                     @OA\Property(property="user_id", type="integer", description="ID of the user who wrote the review")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Course not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", description="Error message indicating the course was not found")
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


    public function courseReview(Request $request, $courseId)
    {
        try {
            // Load the course along with necessary relationships
            $course = Course::with([
                'user', // To include the user who authored the course
                'reviews' => function($query) {
                    $query->orderBy('created_at', 'desc'); // Order reviews by creation date in descending order
                } // To include the user who wrote the review
            ])->findOrFail($courseId);
    
            // Prepare course details
            $courseDetails = [
                'course_id' => $course->id,
                'author' => $course->user->first_name . ' ' . $course->user->last_name,
                'reviews' => $course->reviews->map(function($review) {
                    return [
                        'user' => $review->user->first_name . ' ' . $review->user->last_name,
                        'review_text' => $review->review_text,
                        'rating' => $review->rating,
                        'user_id' => $review->user->id,
                    ];
                }),
            ];
    
            return response()->json($courseDetails);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    
    
}
