<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Course;
use App\Models\User;
use App\Models\Category;
use App\Models\Level;
use App\Models\Lesson;
use App\Models\Review;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;

class CourseByUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_courses_by_user()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a category
        $category = Category::factory()->create();

        // Create a level
        $level = Level::factory()->create();

        // Create a course
        $course = Course::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'level_id' => $level->id,
        ]);

        // Create lessons for the course
        $lesson1 = Lesson::factory()->create(['course_id' => $course->id, 'duration' => '00:30:00']);
        $lesson2 = Lesson::factory()->create(['course_id' => $course->id, 'duration' => '01:15:00']);

        // Create reviews for the course
        $review = Review::factory()->create(['course_id' => $course->id, 'rating' => 5, 'user_id' => $user->id]);

        // Create enrollments for the course
        $enrollment = Enrollment::factory()->create(['course_id' => $course->id, 'user_id' => $user->id]);

        // Call the method
        $response = $this->getJson('/api/users/' . $user->id . '/courses');

        // Get the response data
        $responseData = $response->json();

        // Verify the response data
        $this->assertCount(1, $responseData);
        $this->assertEquals($course->id, $responseData[0]['course_id']);
        $this->assertEquals($course->title, $responseData[0]['course_title']);
        $this->assertEquals($course->tags, $responseData[0]['course_tags']);
        $this->assertEquals($course->thumbnail, $responseData[0]['course_thumbnail']);
        $this->assertEquals($user->first_name . ' ' . $user->last_name, $responseData[0]['author']);
        $this->assertEquals($category->name, $responseData[0]['category_name']);
        $this->assertEquals($course->price, $responseData[0]['course_price']);
        $this->assertEquals(2, $responseData[0]['number_of_lessons']);
        $this->assertEquals(1, $responseData[0]['number_of_enrollments']);
        $this->assertEquals(105, $responseData[0]['duration']); // 30 + 75 minutes
    }
}
