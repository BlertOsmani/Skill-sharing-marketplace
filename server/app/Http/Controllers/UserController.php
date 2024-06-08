<?php

namespace App\Http\Controllers;

use App\Services\Services;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Auth;



class UserController extends Controller
{
    protected $services;
    public function __construct(Services $services) {
        $this->services = $services;
    }

    /**
 * @OA\Post(
 *     path="/api/auth/user/create",
 *     summary="Register a new user",
 *     @OA\RequestBody(
 *         description="Data required to register a new user",
 *         required=true,
 *         @OA\JsonContent(
 *             required={"firstName", "lastName", "email", "username", "password"},
 *             @OA\Property(property="firstName", type="string", description="First name of the user"),
 *             @OA\Property(property="lastName", type="string", description="Last name of the user"),
 *             @OA\Property(property="email", type="string", description="Email address of the user"),
 *             @OA\Property(property="username", type="string", description="Username for the user"),
 *             @OA\Property(property="password", type="string", description="Password for the user")
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="User registered successfully"
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="User already exists or validation failed"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error"
 *     )
 * )
 */


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
                    return response()->json(['success' => 'User registered successfully']);
                }catch(\Exception $e){
                    return response()->json(['message' => 'Validation failed', 'errors' => $e->getMessage()]);
                }
        }}catch(ValidationException $e){
            return response()->json(['errors' => $e->errors()]);
        }
    }

    /**
 * @OA\Post(
 *     path="/api/auth/user/login",
 *     summary="Authenticate user and generate JWT token",
 *     @OA\RequestBody(
 *         description="Credentials needed for authentication",
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username", "password"},
 *             @OA\Property(property="username", type="string", description="User's username"),
 *             @OA\Property(property="password", type="string", description="User's password")
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Authentication successful, JWT token returned"
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Invalid credentials or authentication failed"
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="User not found"
 *     ),
 *     @OA\Response(
 *         response="422",
 *         description="Validation errors"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error"
 *     )
 * )
 */


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
    
    public function createNewToken($token) {
        $user = JWTAuth::user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL(),
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username' => $user->username,
                'email' => $user->email,
                'bio' => $user->bio,
                'profile_picture' => $user->profile_picture
            ]
        ]);
    }


   /**
 * @OA\Get(
 *     path="/api/user",
 *     summary="Returns the authenticated user's details",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(
 *         response=200,
 *         description="Authenticated user's details",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="access_token", type="string", description="JWT Token"),
 *             @OA\Property(property="token_type", type="string", description="Token type, typically 'bearer'"),
 *             @OA\Property(property="expires_in", type="integer", description="Time in minutes until token expiration"),
 *             @OA\Property(
 *                 property="user",
 *                 type="object",
 *                 description="Detailed user information",
 *                 @OA\Property(property="id", type="integer", description="User ID"),
 *                 @OA\Property(property="first_name", type="string", description="User's first name"),
 *                 @OA\Property(property="last_name", type="string", description="User's last name"),
 *                 @OA\Property(property="username", type="string", description="User's username"),
 *                 @OA\Property(property="email", type="string", description="User's email address"),
 *                 @OA\Property(property="bio", type="string", description="User's biography"),
 *                 @OA\Property(property="profile_picture", type="string", description="URL to user's profile picture")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Unauthorized if token is invalid or missing"
 *     )
 * )
 */

    
    public function user() {
        $user = JWTAuth::user();
        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'username' => $user->username,
            'email' => $user->email,
            'bio' => $user->bio,
            'profile_picture' => $user->profile_picture
        ]);
    }

    /**
 * @OA\Post(
 *     path="/api/auth/user/logout",
 *     summary="Logs out the user by invalidating the JWT token",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(
 *         response=200,
 *         description="User successfully logged out",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Success message upon logging out")
 *         )
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error if the logout process fails",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", description="Error message if logout fails")
 *         )
 *     )
 * )
 */

    
    public function logout(){
        try{
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message'=>'User succesfully logged out']);
        }catch(Exception $e){
            return response()->json(['error'=>'Failed to log out, please try again']);
        }
    }

/**
 * @OA\Get(
 *     path="/api/user/toptutors",
 *     summary="Fetches the top 5 tutors based on recent enrollments and reviews",
 *     @OA\Response(
 *         response=200,
 *         description="Successful retrieval of top tutors",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="tutor_id", type="integer", description="The tutor's unique identifier"),
 *                 @OA\Property(property="tutor_name", type="string", description="Full name of the tutor"),
 *                 @OA\Property(property="profile_picture", type="string", description="URL to the tutor's profile picture"),
 *                 @OA\Property(property="tutor_username", type="string", description="Tutor's username"),
 *                 @OA\Property(property="average_rating", type="number", format="float", description="Average rating of the tutor based on recent reviews")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="No tutors found"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         description="Internal server error if there is a problem fetching the data"
 *     )
 * )
 */



    public function getTopTutors(){
        try{
            $pastWeek = Carbon::now()->subWeek();

            $tutors = DB::table('users')
                ->select(
                    'users.id as tutor_id',
                    DB::raw('Concat(users.first_name, " ", users.last_name) as tutor_name'),
                    'users.profile_picture as profile_picture',
                    'users.username as tutor_username',
                    DB::raw('ROUND(IFNULL(AVG(reviews.rating), 0), 1) as average_rating'),
                )
                ->leftJoin('courses', 'courses.user_id' , '=', 'users.id')
                ->leftJoin('enrollments', function($join) use ($pastWeek){
                    $join->on('enrollments.course_id', '=', 'courses.id')
                    ->where('enrollments.enrollment_date',  '>=', $pastWeek);
                })
                ->leftJoin('reviews', 'reviews.course_id', '=', 'courses.id')
                ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.profile_picture')
                ->orderByRaw('Count(Distinct courses.id) Desc, Count(enrollments.id) Desc, Avg(reviews.rating) Desc')
                ->limit(5)
                ->get();

            if($tutors->isEmpty()){
                return response()->json(['message' => "Not tutors found"]);
            }
            return response()->json($tutors);
        }catch(Exception $e){
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
