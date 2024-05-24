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
