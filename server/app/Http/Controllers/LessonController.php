<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class LessonController extends Controller
{

    /**
 * @OA\Get(
 *     path="/api/course/{courseId}/lessons",
 *     summary="Retrieves all lessons for a specific course",
 *     @OA\Parameter(
 *         name="courseId",
 *         in="path",
 *         required=true,
 *         description="The ID of the course to retrieve lessons from",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful retrieval of lessons",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", description="ID of the lesson"),
 *                 @OA\Property(property="course_id", type="integer", description="ID of the course the lesson belongs to"),
 *                 @OA\Property(property="title", type="string", description="Title of the lesson"),
 *                 @OA\Property(property="content", type="string", description="Content of the lesson"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", description="Creation date of the lesson"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Last update date of the lesson")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="No lessons found for the course",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", description="Error message indicating no lessons found")
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


    public function getLessonsByCourse($courseId)
    {
        $lessons = Lesson::where('course_id', $courseId)->get();
        return response()->json($lessons);
    }


    /**
 * @OA\Post(
 *     path="/api/course/lesson/create",
 *     summary="Creates a new lesson for a specified course",
 *     @OA\RequestBody(
 *         description="Data and video needed to create a new lesson",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"course_id", "title", "video_url", "content", "duration"},
 *                 @OA\Property(property="course_id", type="integer", description="ID of the course this lesson belongs to"),
 *                 @OA\Property(property="title", type="string", description="Title of the lesson"),
 *                 @OA\Property(property="video_url", type="string", format="binary", description="Video file for the lesson"),
 *                 @OA\Property(property="content", type="string", description="Content of the lesson"),
 *                 @OA\Property(property="duration", type="integer", description="Duration of the video in seconds")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Lesson created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", description="ID of the newly created lesson"),
 *             @OA\Property(property="video_url", type="string", description="URL of the lesson video"),
 *             @OA\Property(property="title", type="string", description="Title of the lesson"),
 *             @OA\Property(property="content", type="string", description="Content of the lesson"),
 *             @OA\Property(property="duration", type="string", description="Duration of the lesson in HH:MM:SS format"),
 *             @OA\Property(property="course_id", type="integer", description="ID of the course the lesson belongs to")
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
 *             @OA\Property(property="error", type="string", description="Error message indicating an internal error occurred")
 *         )
 *     )
 * )
 */


    public function createLesson(Request $request)
    {
        try {
            Log::info('Request received', $request->all());

            $validatedData = $request->validate([
                'course_id' => 'required|numeric',
                'title' => 'required|string',
                'video_url' => 'required',
                'content' => 'required|string',
                'duration' => 'required|numeric', // Duration in seconds
            ]);



            // Store the uploaded video file
            $lessonVideoPath = $request->file('video_url')->store('lessons', 'public');
            $lessonVideoUrl = url('storage/' . $lessonVideoPath);

            // Convert duration from seconds to time format (H:i:s)
            $duration = gmdate('H:i:s', $validatedData['duration']);

            $lesson = new Lesson();
            $lesson->video_url = $lessonVideoUrl;
            $lesson->title = $validatedData['title'];
            $lesson->content = $validatedData['content'];
            $lesson->duration = $duration;
            $lesson->course_id = $validatedData['course_id'];
            $lesson->save();

            return response()->json($lesson, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
 * @OA\Get(
 *     path="/api/course/{courseId}/lesson",
 *     summary="Retrieves all lessons for a specific course",
 *     @OA\Parameter(
 *         name="courseId",
 *         in="path",
 *         required=true,
 *         description="The ID of the course to retrieve lessons from",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful retrieval of lessons",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", description="ID of the lesson"),
 *                 @OA\Property(property="course_id", type="integer", description="ID of the course the lesson belongs to"),
 *                 @OA\Property(property="title", type="string", description="Title of the lesson"),
 *                 @OA\Property(property="content", type="string", description="Content of the lesson"),
 *                 @OA\Property(property="created_at", type="string", format="date-time", description="Creation date of the lesson"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Last update date of the lesson")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="No lessons found for the course",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", description="Error message indicating no lessons found")
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


    public function getLessons(Request $request, $courseId)
    {
        // Retrieve lessons based on course_id
        $lessons = Lesson::where('course_id', $courseId)->get();

        // Return the lessons as a JSON response
        return response()->json($lessons);
    }

    /**
 * @OA\Post(
 *     path="/api/course/lesson/update/{id}",
 *     summary="Updates an existing lesson",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="The ID of the lesson to be updated",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         description="Data needed to update the lesson",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"title", "duration", "video_url"},
 *                 @OA\Property(property="title", type="string", description="New title of the lesson"),
 *                 @OA\Property(property="content", type="string", description="New content of the lesson, can be nullable"),
 *                 @OA\Property(property="duration", type="integer", description="New duration of the lesson video in seconds"),
 *                 @OA\Property(property="video_url", type="string", format="binary", description="New video file for the lesson")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lesson updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", description="Indicates successful update"),
 *             @OA\Property(property="message", type="string", description="Success message"),
 *             @OA\Property(property="data", type="object", 
 *                 @OA\Property(property="id", type="integer", description="ID of the lesson"),
 *                 @OA\Property(property="title", type="string", description="Updated title of the lesson"),
 *                 @OA\Property(property="content", type="string", description="Updated content of the lesson"),
 *                 @OA\Property(property="duration", type="string", description="Updated duration in HH:MM:SS format"),
 *                 @OA\Property(property="video_url", type="string", description="URL of the updated video")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Lesson not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", description="False indicating update failure"),
 *             @OA\Property(property="message", type="string", description="Error message stating lesson not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", description="Error message detailing the server issue")
 *         )
 *     )
 * )
 */


    public function updateLesson(Request $request, $id)
    {
        try{
            // Retrieve the lesson by id
            $lesson = Lesson::find($id);

            // Check if lesson exists
            if (!$lesson) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lesson not found'
                ], 404);
            }

            // Validate the incoming request data
            $validatedData = $request->validate([
                'title' => 'required|string',
                'content' => 'nullable|string',
                'duration' => 'required|numeric',
                'video_url' => 'required', 
            ]);

            $lessonVideoPath = $request->file('video_url')->store('lessons', 'public');
            $lessonVideoUrl = url('storage/' . $lessonVideoPath);
            $duration = gmdate('H:i:s', $validatedData['duration']);


            $lesson->video_url = $lessonVideoUrl;
            $lesson->title = $validatedData['title'];
            $lesson->content = $validatedData['content'];
            $lesson->duration = $duration;
            $lesson->update();

            // Return a JSON response indicating success
            return response()->json([
                'success' => true,
                'message' => 'Lesson updated successfully',
                'data' => $lesson
            ]);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
 * @OA\Delete(
 *     path="/api/lessons/{id}",
 *     summary="Deletes a specific lesson",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="The ID of the lesson to be deleted",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lesson deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Lesson deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Lesson not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Lesson not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Failed to delete the lesson due to an internal error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Failed to delete the lesson"),
 *             @OA\Property(property="error", type="string", description="Error message detailing the internal error")
 *         )
 *     )
 * )
 */


    public function deleteLesson(Request $request, $id) // Ensure you receive the $id parameter
    {
        // Attempt to retrieve the lesson by ID
        $lesson = Lesson::find($id);

        // Check if the lesson actually exists
        if (!$lesson) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson not found'
            ], 404);
        }

        try {
            // Perform the deletion
            $lesson->delete();

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Lesson deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error deleting lesson: ', ['error' => $e->getMessage()]);

            // Return an error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the lesson',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
