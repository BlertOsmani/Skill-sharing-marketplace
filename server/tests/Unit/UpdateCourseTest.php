<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Course;
use App\Models\User;
use App\Models\Category;
use App\Models\Level;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;

class UpdateCourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_course_success()
    {
        Storage::fake('public');

        // Create a user, category, and level
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $level = Level::factory()->create();

        // Create a course
        $course = Course::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'level_id' => $level->id,
        ]);

        // Prepare update data
        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'price' => 200,
            'category_id' => $category->id,
            'level_id' => $level->id,
            'tags' => 'updated,tags',
            'video' => UploadedFile::fake()->create('video.mp4', 100),
            'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg'),
        ];

        // Call the update method
        $response = $this->postJson('/api/course/update/' . $course->id, $data);

        // Verify the response
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Course updated successfully',
                 ]);

        // Verify the database has the updated data
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'price' => 200,
            'category_id' => $category->id,
            'level_id' => $level->id,
            'tags' => 'updated,tags',
        ]);

        // Verify the files were stored
        $this->assertTrue(Storage::disk('public')->exists('thumbnails/' . $data['thumbnail']->hashName()));
        $this->assertTrue(Storage::disk('public')->exists('videos/' . $data['video']->hashName()));
    }

    public function test_update_course_not_found()
    {
        // Call the update method with a non-existing course id
        $response = $this->postJson('/api/course/update/9999', [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'price' => 200,
            'category_id' => 1,
            'level_id' => 1,
            'tags' => 'updated,tags',
            'video' => UploadedFile::fake()->create('video.mp4', 100),
            'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg'),
        ]);

        // Verify the response
        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Course not found',
                 ]);
    }

    public function test_update_course_validation_failure()
    {
        // Create a user, category, and level
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $level = Level::factory()->create();

        // Create a course
        $course = Course::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'level_id' => $level->id,
        ]);

        // Call the update method with invalid data
        $response = $this->postJson('/api/course/update/' . $course->id, [
            'title' => '',
            'description' => 'Updated Description',
            'price' => 200,
            'category_id' => $category->id,
            'level_id' => $level->id,
            'tags' => 'updated,tags',
            'video' => UploadedFile::fake()->create('video.mp4', 100),
            'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg'),
        ]);

        // Verify the response
        $response->assertStatus(422);
    }

    public function test_update_course_failure()
    {
        Storage::fake('public');

        // Create a user, category, and level
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $level = Level::factory()->create();

        // Create a course
        $course = Course::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'level_id' => $level->id,
        ]);

        // Mock the Course model's update method to throw an exception
        $courseMock = Mockery::mock($course)->makePartial();
        $courseMock->shouldReceive('save')->andThrow(new \Exception('Update failed'));

        // Replace the actual course instance with the mocked instance in the IoC container
        $this->app->instance(get_class($course), $courseMock);

        // Prepare update data
        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'price' => 200,
            'category_id' => $category->id,
            'level_id' => $level->id,
            'tags' => 'updated,tags',
            'video' => UploadedFile::fake()->create('video.mp4', 100),
            'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg'),
        ];

        // Call the update method
        $response = $this->postJson('/api/course/update/' . $course->id, $data);

        // Verify the response
        $response->assertStatus(500);
        $response->assertJson(['errors']);

        // Ensure Mockery expectations are met
        Mockery::close();
    }
}
