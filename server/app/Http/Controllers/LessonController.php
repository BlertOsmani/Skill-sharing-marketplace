<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class LessonController extends Controller
{
    public function getLessonsByCourse($courseId)
    {
        $lessons = Lesson::where('course_id', $courseId)->get();
        return response()->json($lessons);
    }
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

    public function getLessons(Request $request, $courseId)
    {
        // Retrieve lessons based on course_id
        $lessons = Lesson::where('course_id', $courseId)->get();

        // Return the lessons as a JSON response
        return response()->json($lessons);
    }
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
