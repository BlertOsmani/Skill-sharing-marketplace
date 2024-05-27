<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Course;

class ReviewController extends Controller
{
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

    public function courseReview(Request $request, $courseId)
    {
        try {
            // Load the course along with necessary relationships
            $course = Course::with([
                'user', // To include the user who authored the course
                'reviews' // To include the user who wrote the review
            ])->findOrFail($courseId);
    
            // Prepare course details
            $courseDetails = [
                'course_id' => $course->id,
                'author' => $course->user->first_name . ' ' . $course->user->last_name,
                'reviews' => $course->reviews->map(function($review) {
                    return [
                        'review_text' => $review->review_text,
                        'rating' => $review->rating,
                    ];
                }),
            ];
    
            return response()->json($courseDetails);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    
    
}
