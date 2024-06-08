<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class DeleteCourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_course_success()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a course
        $course = Course::factory()->create([
            'user_id' => $user->id,
        ]);

        // Call the delete method
        $response = $this->deleteJson('/api/course/delete/' . $course->id);

        // Verify the response
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Course deleted successfully'
                 ]);

        // Check that the course is deleted
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    public function test_delete_course_not_found()
    {
        // Call the delete method with a non-existing course id
        $response = $this->deleteJson('/api/course/delete/9999');

        // Verify the response
        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Course not found'
                 ]);
    }

    public function test_delete_course_failure()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a course
        $course = Course::factory()->create([
            'user_id' => $user->id,
        ]);

        // Mock the delete method on the course instance to throw an exception
        $courseMock = Mockery::mock(Course::class)->makePartial();
        $courseMock->shouldReceive('find')->with($course->id)->andReturn($courseMock);
        $courseMock->shouldReceive('delete')->andThrow(new \Exception('Delete failed'));

        // Replace the actual course instance with the mocked instance in the IoC container
        $this->app->instance(Course::class, $courseMock);

        // Call the delete method
        $response = $this->deleteJson('/api/course/delete/' . $course->id);

        // Verify the response
        $response->assertStatus(500)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Failed to delete the Course',
                     'error' => 'Delete failed'
                 ]);

        // Ensure Mockery expectations are met
        Mockery::close();
    }
}
