<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnrollInCoursesTest extends TestCase
{
    use RefreshDatabase;

    public function test_enroll_success()
    {
        // Create a user and a course
        $user = User::factory()->create();
        $course = Course::factory()->create([
            'user_id' => $user->id
        ]);

        // Call the enroll method
        $response = $this->postJson('/api/course/' . $course->id . '/enroll', [
            'user_id' => $user->id,
        ]);

        // Verify the response
        $response->assertStatus(201);
        $response->assertJsonStructure();

        // Verify the enrollment was created in the database
        $this->assertDatabaseHas('enrollments', [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrollment_status' => 'enrolled',
        ]);
    }

    public function test_enroll_validation_error()
    {
        // Create a user and a course
        $user = User::factory()->create();
        $course = Course::factory()->create([
            'user_id' => $user->id
        ]);

        // Call the enroll method with an invalid user_id
        $response = $this->postJson('/api/course/' . $course->id . '/enroll', [
            'user_id' => null,
        ]);

        // Verify the response
        $response->assertStatus(422)
                 ->assertJsonStructure(['errors' => ['user_id']]);
    }

    public function test_enroll_user_not_found()
    {
        // Create a user and a course
        $user = User::factory()->create();
        $course = Course::factory()->create([
            'user_id' => $user->id
        ]);

        // Call the enroll method with a non-existing user_id
        $response = $this->postJson('/api/course/' . $course->id . '/enroll', [
            'user_id' => 9999,
        ]);

        // Verify the response
        $response->assertStatus(422)
                 ->assertJsonStructure(['errors' => ['user_id']]);
    }
}
