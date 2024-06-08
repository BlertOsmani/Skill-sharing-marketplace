<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;




class CourseController extends Controller
{

    /**
 * @OA\Get(
 *     path="/api/course/enrolled",
 *     summary="Retrieves courses a user is enrolled in",
 *     @OA\Parameter(
 *         name="userId",
 *         in="query",
 *         required=true,
 *         description="The ID of the user whose enrolled courses are to be retrieved",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         description="Limit the number of courses returned",
 *         required=false,
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
 *                 @OA\Property(property="course_id", type="integer", description="Course ID"),
 *                 @OA\Property(property="course_author", type="string", description="Author of the course"),
 *                 @OA\Property(property="course_title", type="string", description="Title of the course"),
 *                 @OA\Property(property="category_name", type="string", description="Category of the course"),
 *                 @OA\Property(property="level_name", type="string", description="Level of the course"),
 *                 @OA\Property(property="course_tags", type="string", description="Tags associated with the course"),
 *                 @OA\Property(property="course_thumbnail", type="string", description="Thumbnail image URL of the course"),
 *                 @OA\Property(property="enrollments_count", type="integer", description="Number of enrollments in the course")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="No courses found"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error"
 *     )
 * )
 */


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
                ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
                ->join('categories', 'courses.category_id', '=', 'categories.id')
                ->join('users', 'users.id', '=', 'courses.user_id')
                ->join('levels', 'courses.level_id', '=', 'levels.id')
                ->where('enrollments.user_id', $userId)
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


    /**
 * @OA\Get(
 *     path="/api/course/featured/{limit}",
 *     summary="Retrieves featured courses based on enrollments and reviews",
 *     @OA\Parameter(
 *         name="limit",
 *         in="path",
 *         required=true,
 *         description="The number of featured courses to retrieve",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful retrieval of featured courses",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="course_id", type="integer", description="ID of the course"),
 *                 @OA\Property(property="course_title", type="string", description="Title of the course"),
 *                 @OA\Property(
 *                     property="course_tags",
 *                     type="array",
 *                     description="Tags associated with the course",
 *                     @OA\Items(type="string", description="A single tag related to the course")
 *                 ),
 *                 @OA\Property(property="course_thumbnail", type="string", description="URL of the course thumbnail"),
 *                 @OA\Property(property="author", type="string", description="Name of the course author"),
 *                 @OA\Property(property="category_name", type="string", description="Category of the course"),
 *                 @OA\Property(property="course_price", type="number", description="Price of the course"),
 *                 @OA\Property(property="number_of_lessons", type="integer", description="Number of lessons in the course"),
 *                 @OA\Property(property="number_of_enrollments", type="integer", description="Number of enrollments in the course")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="No course found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="No course found")
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




    public function getFeaturedCourses(Request $request, $limit){
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

        /**
 * @OA\Get(
 *     path="/api/course/details/{courseId}",
 *     summary="Retrieves detailed information about a specific course",
 *     @OA\Parameter(
 *         name="courseId",
 *         in="path",
 *         required=true,
 *         description="The ID of the course",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detailed information about the course",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="course_id", type="integer", description="Course ID"),
 *             @OA\Property(property="course_title", type="string", description="Title of the course"),
 *             @OA\Property(property="course_description", type="string", description="Description of the course"),
 *             @OA\Property(property="course_tags", type="array", @OA\Items(type="string"), description="Tags associated with the course"),
 *             @OA\Property(property="course_thumbnail", type="string", description="URL of the course thumbnail image"),
 *             @OA\Property(property="course_video", type="string", description="URL of the main course video"),
 *             @OA\Property(property="author", type="string", description="Full name of the course author"),
 *             @OA\Property(property="category_name", type="string", description="Category of the course"),
 *             @OA\Property(property="course_price", type="number", format="float", description="Price of the course"),
 *             @OA\Property(property="course_level", type="string", description="Difficulty level of the course"),
 *             @OA\Property(property="lessons", type="array", 
 *                 @OA\Items(type="object", 
 *                     @OA\Property(property="title", type="string", description="Title of the lesson"),
 *                     @OA\Property(property="duration", type="string", description="Duration of the lesson")
 *                 ),
 *                 description="List of lessons in the course"
 *             ),
 *             @OA\Property(property="total_lesson_duration", type="number", description="Total duration of all lessons, in minutes"),
 *             @OA\Property(property="number_of_lessons", type="integer", description="Total number of lessons in the course"),
 *             @OA\Property(property="number_of_enrollments", type="integer", description="Total number of enrollments in the course"),
 *             @OA\Property(property="average_rating", type="number", format="float", description="Average rating of the course"),
 *             @OA\Property(property="reviews", type="array", 
 *                 @OA\Items(type="object", 
 *                     @OA\Property(property="user_id", type="integer", description="ID of the user who reviewed"),
 *                     @OA\Property(property="user", type="string", description="Name of the user who reviewed"),
 *                     @OA\Property(property="rating", type="integer", description="Rating given by the user"),
 *                     @OA\Property(property="review_text", type="string", description="Text of the review")
 *                 ),
 *                 description="List of reviews for the course"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error if there is a problem fetching course details"
 *     )
 * )
 */


    public function courseDetails(Request $request, $courseId)
    {
    
        try {
            // Load the course along with necessary relationships
            $course = Course::with([
                'user',
                'category',
                'level',
                'lessons',
                'reviews.user', // To include the user who wrote the review
                'enrollments'
            ])->findOrFail($courseId);
    
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

    /**
 * @OA\Post(
 *     path="/api/course/create",
 *     summary="Creates a new course",
 *     @OA\RequestBody(
 *         description="Data needed to create a new course",
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "title", "description", "category_id", "level_id", "video", "thumbnail"},
 *             @OA\Property(property="user_id", type="integer", description="ID of the user creating the course"),
 *             @OA\Property(property="title", type="string", description="Title of the course"),
 *             @OA\Property(property="description", type="string", description="Description of the course"),
 *             @OA\Property(property="price", type="number", format="float", description="Price of the course, can be omitted for free courses"),
 *             @OA\Property(property="category_id", type="integer", description="ID of the category the course belongs to"),
 *             @OA\Property(property="level_id", type="integer", description="ID of the difficulty level of the course"),
 *             @OA\Property(property="tags", type="string", description="Comma-separated tags associated with the course"),
 *             @OA\Property(property="video", type="string", format="binary", description="Video file for the course"),
 *             @OA\Property(property="thumbnail", type="string", format="binary", description="Thumbnail image for the course")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Course created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", description="ID of the newly created course"),
 *             @OA\Property(property="title", type="string", description="Title of the course"),
 *             @OA\Property(property="description", type="string", description="Description of the course"),
 *             @OA\Property(property="price", type="number", format="float", description="Price of the course"),
 *             @OA\Property(property="category_id", type="integer", description="Category ID of the course"),
 *             @OA\Property(property="level_id", type="integer", description="Level ID of the course"),
 *             @OA\Property(property="tags", type="string", description="Tags associated with the course"),
 *             @OA\Property(property="thumbnail", type="string", description="URL of the course thumbnail"),
 *             @OA\Property(property="video", type="string", description="URL of the course video")
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error or file upload failure"
 *     )
 * )
 */



    public function createCourse(Request $request)
    {
        // Validate the request
        try {
            // Validate request data
            $validatedData = $request->validate([
                'user_id' => 'required|numeric',
                'title' => 'required|string',
                'description' => 'required|string',
                'price' => 'numeric',
                'category_id' => 'required|numeric',
                'level_id' => 'required|numeric',
                'tags' => 'string',
                'video' => 'required', // Adjust max size as needed
                'thumbnail' => 'required', // Adjust max size as needed
            ]);
    
            // Store the uploaded files
            try {
                $coverImagePath = $request->file('thumbnail')->store('thumbnails', 'public');
                $salesVideoPath = $request->file('video')->store('videos', 'public');
            } catch (\Exception $e) {
                Log::error('File storage error: ' . $e->getMessage());
                return response()->json(['error' => 'File upload failed. Please try again.'], 500);
            }
    
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json(['errors' => $e->getMessage()]);
        } catch (\Exception $e) {
            // Handle any other errors
            Log::error('Course creation error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the course. Please try again.'], 500);
        }
    }

    /**
 * @OA\Get(
 *     path="/api/users/{userId}/courses",
 *     summary="Retrieves all courses created by a specific user",
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         required=true,
 *         description="The ID of the user whose courses are to be retrieved",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="A list of courses created by the user",
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
 *                 @OA\Property(property="number_of_enrollments", type="integer", description="Total number of enrollments in the course"),
 *                 @OA\Property(property="duration", type="number", format="float", description="Total duration of all lessons in the course in minutes")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="No courses found for the user"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error"
 *     )
 * )
 */


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

    /**
 * @OA\Delete(
 *     path="/api/course/delete/{id}",
 *     summary="Deletes a specific course",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="The ID of the course to be deleted",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Course deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Course deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Course not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Course not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Failed to delete the course due to an internal error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Failed to delete the Course"),
 *             @OA\Property(property="error", type="string", description="Error message detailing the internal error")
 *         )
 *     )
 * )
 */


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

    /**
 * @OA\Post(
 *     path="/api/course/update/{id}",
 *     summary="Updates a specific course",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="The ID of the course to be updated",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         description="Data and files needed to update the course",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"title", "category_id", "level_id", "video", "thumbnail"},
 *                 @OA\Property(property="title", type="string", description="Title of the course"),
 *                 @OA\Property(property="description", type="string", description="Description of the course"),
 *                 @OA\Property(property="price", type="number", format="float", description="Price of the course"),
 *                 @OA\Property(property="category_id", type="integer", description="ID of the category the course belongs to"),
 *                 @OA\Property(property="level_id", type="integer", description="ID of the difficulty level of the course"),
 *                 @OA\Property(property="tags", type="string", description="Comma-separated tags associated with the course"),
 *                 @OA\Property(property="video", type="string", format="binary", description="Video file for the course"),
 *                 @OA\Property(property="thumbnail", type="string", format="binary", description="Thumbnail image for the course")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Course updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Course updated successfully"),
 *             @OA\Property(property="data", type="object", description="Updated course data")
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Course not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Course not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Failed to update the course due to an internal error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", description="Error message detailing the internal error")
 *         )
 *     )
 * )
 */


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

