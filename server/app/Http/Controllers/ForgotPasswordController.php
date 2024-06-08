<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;

class ForgotPasswordController extends Controller
{

    /**
 * @OA\Post(
 *     path="/api/forgot/password",
 *     summary="Sends a password reset link to the user's email",
 *     @OA\RequestBody(
 *         description="Email address to which the reset link will be sent",
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(property="email", type="string", format="email", description="User's email address")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reset link sent successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Success message indicating the reset link was sent")
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Validation error or the email is not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", description="Error message detailing the issue with the request")
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


    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
    
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'Email not found.'], 400);
        }
    
        $status = Password::broker()->sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                $user->notify(new \App\Notifications\ResetPasswordNotification($token));
            }
        );
    
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)], 200);
        } else {
            return response()->json(['error' => __($status)], 400);
        }
    }
}
