<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request){
        $query = $request->input('query');
        $searchUsers = $this->searchUsers($query);
        $searchCourses = $this->searchCourses($query);

        return response()->json(
            [
                'users' => $searchUsers,
                'courses' => $searchCourses
            ]
        );
    }

    public function searchUsers($query){
        return User::select('id', 'first_name', 'last_name', 'username', 'profile_picture')
        ->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"])
        ->orWhere('username', 'LIKE', "%{$query}%")
        ->get();
    }
    public function searchCourses($query)
    {
        return Course::with(['user', 'category', 'lessons', 'enrollments'])
        ->where(function($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%")
              ->orWhere('tags', 'LIKE', "%{$query}%");
        })
        ->orWhereHas('category', function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%");
        })
        ->orWhereHas('level', function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%");
        })
        ->orWhereHas('lessons', function($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%");
        })
        ->orWhereHas('user', function($q) use ($query){
            $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"])
                ->orWhere('username', 'LIKE', "%{$query}%");
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
                'number_of_lessons' => $course->lessons->count(),
                'number_of_enrollments' => $course->enrollments->count(),
            ];
        });
    }
    
}
