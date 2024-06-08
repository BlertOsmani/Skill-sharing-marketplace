<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Services\Services;


class ResetPasswordController extends Controller
{

    /**
 * @OA\Get(
 *     path="/api/password/reset/{token}",
 *     summary="Displays the password reset form",
 *     @OA\Parameter(
 *         name="token",
 *         in="path",
 *         required=true,
 *         description="Password reset token",
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset form is displayed",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Displaying the password reset form")
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Token not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", description="Error message indicating the token was not found or is invalid")
 *         )
 *     )
 * )
 */



    /**
 * @OA\Post(
 *     path="/api/password/reset",
 *     summary="Resets a user's password using a reset token",
 *     @OA\RequestBody(
 *         description="Required data for resetting the password",
 *         required=true,
 *         @OA\JsonContent(
 *             required={"token", "email", "password", "password_confirmation"},
 *             @OA\Property(property="token", type="string", description="Password reset token"),
 *             @OA\Property(property="email", type="string", format="email", description="User's email address"),
 *             @OA\Property(property="password", type="string", format="password", description="New password"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", description="Confirmation of the new password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Success message indicating the password was reset successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Failed to reset the password",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", description="Error message detailing why the password reset failed")
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



    protected $services;

    public function __construct(Services $services) {
        $this->services = $services;
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',   
        ]);

        $password_salt = $this->services->generateSalt();
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($password_salt) {
                $user->forceFill([
                    'password' => Hash::make($password.$password_salt)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully'], 200);
        } else {
            return response()->json(['error' => __($status)], 400);
        }
    }   
}
