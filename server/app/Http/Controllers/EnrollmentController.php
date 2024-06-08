<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/course/{courseId}/enroll",
 *     summary="Enrolls a user into a specified course",
 *     @OA\Parameter(
 *         name="courseId",
 *         in="path",
 *         required=true,
 *         description="The ID of the course to enroll in",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         description="User ID needed to enroll in the course",
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id"},
 *             @OA\Property(property="user_id", type="integer", description="The user's ID")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Enrollment successful",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="user_id", type="integer", description="User's ID"),
 *             @OA\Property(property="course_id", type="integer", description="Course ID"),
 *             @OA\Property(property="enrollment_status", type="string", description="Status of the enrollment"),
 *             @OA\Property(property="enrollment_date", type="string", format="date-time", description="Date and time of enrollment")
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Validation error or user does not exist"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error"
 *     )
 * )
 */


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
