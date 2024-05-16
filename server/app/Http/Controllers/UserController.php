<?php

namespace App\Http\Controllers;

use App\Services\Services;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
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
