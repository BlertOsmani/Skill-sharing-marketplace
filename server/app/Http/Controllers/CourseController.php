<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function getEnrolledCourses(Request $request){
        $limit = $request->input('limit');
        try {
            $query = Course::select(
                'courses.id as course_id',
                DB::raw('CONCAT(users.first_name, " ", users.last_name) as course_author'),
                'courses.title as course_title',
                'categories.name as category_name',
                'levels.name as level_name',
                'courses.tags as course_tags',
                'courses.thumbnail as course_thumbnail'
            )
                ->join('categories', 'courses.category_id', '=', 'categories.id')
                ->join('users', 'users.id', '=', 'courses.user_id')
                ->join('levels', 'courses.level_id', '=', 'levels.id')
                ->withCount('enrollments');
            if($limit !== null){
                $query->limit((int)$limit);
            }
            $courses = $query->get();
    
            if ($courses->isEmpty()) {
                return response()->json(['message' => 'No courses found.']);
            }
    
            return response()->json($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function getFeaturedCourses(Request $request){
        $limit = $request->input('limit');
        try{
            $featured_courses = Course::withCount('enrollments')
                        ->withAvg('reviews', 'rating')
                        ->orderByDesc('enrollments_count')
                        ->orderByDesc('reviews_avg_rating')
                        ->take($limit)
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
            if($featured_courses->isEmpty()){
                return response()->json(['message' => 'No course found']);
            }
            return response()->json($featured_courses);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function courseDetails(Request $request)
    {
        $id = $request->input('course_id');
    
        try {
            // Load the course along with necessary relationships
            $course = Course::with([
                'user',
                'category',
                'level',
                'lessons',
                'reviews.user', // To include the user who wrote the review
                'enrollments'
            ])->findOrFail($id);
    
            // Calculate the average rating
            $averageRating = number_format($course->reviews->avg('rating'), 1);
    
            // Sum of lesson durations in minutes
            $totalLessonDuration = $course->lessons->sum(function($lesson) {
                $parts = explode(':', $lesson->duration);
                $hours = isset($parts[0]) ? (int)$parts[0] : 0;
                $minutes = isset($parts[1]) ? (int)$parts[1] : 0;
                $seconds = isset($parts[2]) ? (int)$parts[2] : 0;
                return $hours * 60 + $minutes + $seconds / 60;
            });
    
            // Prepare course details
            $courseDetails = [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'course_description' => $course->description,
                'course_tags' => $course->tags,
                'course_thumbnail' => $course->thumbnail,
                'course_video' => $course->video,
                'author' => $course->user->first_name . ' ' . $course->user->last_name,
                'category_name' => $course->category->name,
                'course_price' => $course->price,
                'course_level' => $course->level->name,
                'lessons' => $course->lessons->map(function($lesson) {
                    return [
                        'title' => $lesson->title,
                        'duration' => $lesson->duration,
                    ];
                }),
                'total_lesson_duration' => $totalLessonDuration,
                'number_of_lessons' => $course->lessons->count(),
                'number_of_enrollments' => $course->enrollments->count(),
                'average_rating' => $averageRating,
                'reviews' => $course->reviews->map(function($review) {
                    return [
                        'user_id' => $review->user->id,
                        'user' => $review->user->first_name . ' ' . $review->user->last_name,
                        'rating' => $review->rating,
                        'review_text' => $review->review_text,
                    ];
                }),
            ];
    
            return response()->json($courseDetails);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    



}
