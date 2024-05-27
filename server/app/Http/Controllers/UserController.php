<?php

namespace App\Http\Controllers;

use App\Services\Services;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\JWTAuth;
use Validator;
use Auth;


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
                $user->first_name = $data['firstName'];
                $user->last_name = $data['lastName'];
                $user->email = $data['email'];
                $user->username = $data['username'];
                $user->password = $password_hash;
                $user->password_salt = $password_salt;
                $user->created_at = now();
                $user->updated_at = now();
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

    public function login(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = User::where('username', $request->username)->first();

            if (!$user) {
                return response()->json(['error' => 'User not found with username: ' . $request->username], 404);
            }
            $passwordWithSalt = $request->password.$user->password_salt;


            if (!Hash::check($passwordWithSalt, $user->password)) {
                return response()->json(['error' => 'Incorrect password for user: ' . $user->username], 401);
            }

            if (!$token = JWTAuth::attempt(['username' => $request->username, 'password' => $passwordWithSalt])) {
                return response()->json(['error' => 'Authentication failed for user: ' . $request->username], 401);
            }

            return $this->createNewToken($token);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL()*60 * 1440 * 10,
            'user' => JWTAuth::user()
        ]);

    }
    public function user(){
        return response()->json(JWTAuth::user());
    }
    public function logout(){
        try{
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message'=>'User succesfully logged out']);
        }catch(Exception $e){
            return response()->json(['error'=>'Failed to log out, please try again']);
    }
}
}
