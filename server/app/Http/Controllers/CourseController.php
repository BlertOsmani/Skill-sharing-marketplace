<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function getEnrolledCourses(Request $request){
        $limit = $request->input('limit');
        $userId = $request->input('userId');
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
                ->where('users.id', $userId)
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
    public function createCourse(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'user_id' => 'required|numeric',
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'numeric',
            'category_id' => 'required|numeric',
            'level_id' => 'required|numeric',
            'tags' => 'string',
            'video' => 'required',
            'thumbnail' => 'required',
        ]);

        // Store the uploaded files
         $coverImagePath = $request->file('thumbnail')->store('thumbnails', 'public');
         $salesVideoPath = $request->file('video')->store('videos', 'public');

        $coverImageUrl = url('storage/' . $coverImagePath);
        $salesVideoUrl = url('storage/' . $salesVideoPath);


        // Create and save the course
        $course = new Course();
        $course->user_id = $validatedData['user_id'];
        $course->title = $validatedData['title'];
        $course->description = $validatedData['description'];
        $course->price = $validatedData['price'];
        $course->category_id = $validatedData['category_id'];
        $course->level_id = $validatedData['level_id'];
        $course->tags = $validatedData['tags'];
        $course->thumbnail = asset($coverImageUrl);
        $course->video = asset($salesVideoUrl);
        $course->save();


        return response()->json($course, 201);
    }
    public function getCoursesByUser(Request $request, $userId) {
        $courses = Course::whereHas('user', function ($query) use ($userId) {
                            $query->where('id', $userId);
                        })
                        ->with(['user', 'category', 'level', 'lessons', 'reviews.user', 'enrollments'])
                        ->withCount('enrollments')
                        ->withAvg('reviews', 'rating')
                        ->orderByDesc('enrollments_count')
                        ->orderByDesc('reviews_avg_rating')
                        ->get()
                        ->map(function($course) {
                            $totalLessonDuration = $course->lessons->sum(function($lesson) {
                                $parts = explode(':', $lesson->duration);
                                $hours = isset($parts[0]) ? (int)$parts[0] : 0;
                                $minutes = isset($parts[1]) ? (int)$parts[1] : 0;
                                $seconds = isset($parts[2]) ? (int)$parts[2] : 0;
                                return $hours * 60 + $minutes + $seconds / 60;
                            });
    
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
                                'duration' => $totalLessonDuration
                            ];
                        });
    
        return response()->json($courses);
    }
    public function deleteCourse(Request $request, $id){
        $course = Course::find($id);

        if(!$course){
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }
        try {
            // Perform the deletion
            $course->delete();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error deleting course: ', ['error' => $e->getMessage()]);

            // Return an error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the Course',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateCourse(Request $request, $id)
    {
        try{
            // Retrieve the lesson by id
            $course = Course::find($id);

            // Check if lesson exists
            if (!$course) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course not found'
                ], 404);
            }

            // Validate the incoming request data
            $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'string',
            'price' => 'numeric',
            'category_id' => 'required|numeric',
            'level_id' => 'required|numeric',
            'tags' => 'string',
            'video' => 'required',
            'thumbnail' => 'required',
            ]);

            $coverImagePath = $request->file('thumbnail')->store('thumbnails', 'public');
         $salesVideoPath = $request->file('video')->store('videos', 'public');

        $coverImageUrl = url('storage/' . $coverImagePath);
        $salesVideoUrl = url('storage/' . $salesVideoPath);



        $course->title = $validatedData['title'];
        $course->description = $validatedData['description'];
        $course->price = $validatedData['price'];
        $course->category_id = $validatedData['category_id'];
        $course->level_id = $validatedData['level_id'];
        $course->tags = $validatedData['tags'];
        $course->thumbnail = asset($coverImageUrl);
        $course->video = asset($salesVideoUrl);
            $course->update();

            // Return a JSON response indicating success
            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully',
                'data' => $course
            ]);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}

