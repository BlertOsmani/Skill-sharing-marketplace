<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{
    public function enroll(Request $request, $courseId)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $enrollment = new Enrollment();
        $enrollment->user_id = $request->user_id;
        $enrollment->course_id = $courseId;
        $enrollment->enrollment_status = 'enrolled';
        $enrollment->enrollment_date = now();
        $enrollment->save();

        return response()->json($enrollment, 201);
    }
}
