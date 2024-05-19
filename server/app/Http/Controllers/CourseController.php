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
        
    }
}
