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

class GetCourseDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_details()
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
        $response = $this->getJson('api/course/details/' . $course->id);

        // Get the response data
        $responseData = $response->json();

        // Verify the response data
        $this->assertEquals($course->id, $responseData['course_id']);
        $this->assertEquals($course->title, $responseData['course_title']);
        $this->assertEquals($course->description, $responseData['course_description']);
        $this->assertEquals($course->tags, $responseData['course_tags']);
        $this->assertEquals($course->thumbnail, $responseData['course_thumbnail']);
        $this->assertEquals($course->video, $responseData['course_video']);
        $this->assertEquals($user->first_name . ' ' . $user->last_name, $responseData['author']);
        $this->assertEquals($category->name, $responseData['category_name']);
        $this->assertEquals($course->price, $responseData['course_price']);
        $this->assertEquals($level->name, $responseData['course_level']);
        $this->assertEquals(2, $responseData['number_of_lessons']);
        $this->assertEquals(1, $responseData['number_of_enrollments']);
        $this->assertEquals(105, $responseData['total_lesson_duration']); // 30 + 75 minutes
        $this->assertEquals(5.0, $responseData['average_rating']);

        // Verify lessons
        $this->assertCount(2, $responseData['lessons']);
        $this->assertEquals($lesson1->title, $responseData['lessons'][0]['title']);
        $this->assertEquals($lesson1->duration, $responseData['lessons'][0]['duration']);
        $this->assertEquals($lesson2->title, $responseData['lessons'][1]['title']);
        $this->assertEquals($lesson2->duration, $responseData['lessons'][1]['duration']);

        // Verify reviews
        $this->assertCount(1, $responseData['reviews']);
        $this->assertEquals($review->user->id, $responseData['reviews'][0]['user_id']);
        $this->assertEquals($review->user->first_name . ' ' . $review->user->last_name, $responseData['reviews'][0]['user']);
        $this->assertEquals($review->rating, $responseData['reviews'][0]['rating']);
        $this->assertEquals($review->review_text, $responseData['reviews'][0]['review_text']);
    }
}