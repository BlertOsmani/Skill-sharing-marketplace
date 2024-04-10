<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class Services
{
    public function requestAndValidateFields_SignUp(){
        return request()->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'password' => 'required|min:10|max:50|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            'confirmPassword' => 'same:password'
        ], [
            'firstName.required' => "First name is required",
            'lastName.required' => "Last name is required",
            'email.required' => 'Email is required',
            'username.required' => 'Username is required',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 10 letters',
            'password.max' => "Password cannot be more than 50 letter",
            'password.regex' => "Password must contain at least 1 lowercase letter, 1 uppercase letter, 1 number and 1 symbol",
            'confirmPassword'=> 'Passwords do not match'
        ]);
    }

    public function userexists_SignUp(string $email): bool{
        return User::where('email', $email)->exists();
    }

    public function generateSalt():string{
        return bcrypt(Str::random(16));
    }
}
