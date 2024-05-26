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
}
