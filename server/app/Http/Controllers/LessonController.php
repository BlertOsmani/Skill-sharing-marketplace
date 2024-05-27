<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;

class LessonController extends Controller
{
    public function getLessonsByCourse($courseId)
    {
        $lessons = Lesson::where('course_id', $courseId)->get();
        return response()->json($lessons);
    }
}
