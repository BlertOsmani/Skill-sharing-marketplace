<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCoursesByCategory(Request $request)
    {
        $category_id = $request->input('category_id');
        $courses = Course::with(['category', 'user', 'lessons', 'enrollments'])
            ->whereHas('category', function($q) use ($category_id) {
                $q->where('id', $category_id);
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
                    'course_price' => $course->price,
                    'number_of_lessons' => $course->lessons->count(),
                    'number_of_enrollments' => $course->enrollments->count(),
                ];
            });

        return response()->json($courses);
    }
}
