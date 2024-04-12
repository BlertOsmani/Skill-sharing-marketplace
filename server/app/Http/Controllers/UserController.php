<?php

namespace App\Http\Controllers;

use App\Services\Services;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected $services;
    public function __construct(Services $services) {
        $this->services = $services;
    }

    public function register(Request $request){
        try{
            $data = $this->services->requestAndValidateFields_SignUp();

            $userexists = $this->services->userexists_SignUp($data['email']);

            if($userexists){
                return response()->json(['message' => 'User already exists']);
            }
            else{
                $password_salt = $this->services->generateSalt();
                $password_hash = Hash::make($data['password'].$password_salt);
                $user = new User();
                $user->firstName = $data['firstName'];
                $user->lastName = $data['lastName'];
                $user->email = $data['email'];
                $user->username = $data['username'];
                $user->password_hash = $password_hash;
                $user->password_salt = $password_salt;
                $user->created_at = now();
                $user->modified_at = now();
                try{
                    $user->save();
                    return response()->json(['message' => 'User registered successfully']);
                }catch(\Exception $e){
                    return response()->json(['message' => 'Validation failed', 'errors' => $e->getMessage()]);
                }
        }}catch(ValidationException $e){
            return response()->json(['errors' => $e->errors()]);
        }
    }
}
