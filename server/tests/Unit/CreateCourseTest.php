<?php 

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\CourseController;
use App\Models\Course;
use App\Models\User;
use App\Models\Category;
use App\Models\Level;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_course_success()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category = Category::factory()->create();
        $level = Level::factory()->create();

        $request = Request::create('api/course/create', 'POST', [
            'user_id' => $user->id,
            'title' => 'Test Course',
            'description' => 'This is a test course',
            'price' => 100,
            'category_id' => $category->id,
            'level_id' => $level->id,
            'tags' => 'test, course',
        ], [], [
            'video' => UploadedFile::fake()->create('video.mp4', 40),
            'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg'),
        ]);

        $controller = new CourseController();

        $response = $controller->createCourse($request);

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertDatabaseHas('courses', [
            'title' => 'Test Course',
            'description' => 'This is a test course',
            'price' => 100,
            'category_id' => $category->id,
            'level_id' => $level->id,
            'tags' => 'test, course',
        ]);

        $thumbnailPath = 'thumbnails/' . $request->file('thumbnail')->hashName();
        $videoPath = 'videos/' . $request->file('video')->hashName();

        $this->assertTrue(Storage::disk('public')->exists($thumbnailPath));
        $this->assertTrue(Storage::disk('public')->exists($videoPath));
    }

    public function test_create_course_validation_error()
    {
        $response = $this->postJson('/api/course/create', [
            'user_id' => null,
            'title' => '',
            'description' => '',
            'price' => '',
            'category_id' => null,
            'level_id' => null,
            'tags' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }

    public function test_create_course_file_storage_error()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category = Category::factory()->create();
        $level = Level::factory()->create();

        $request = Request::create('api/course/create', 'POST', [
            'user_id' => $user->id,
            'title' => 'Test Course',
            'description' => 'This is a test course',
            'price' => 100,
            'category_id' => $category->id,
            'level_id' => $level->id,
            'tags' => 'test, course',
        ], [], [
            'video' => UploadedFile::fake()->create('video.mp4', 40),
            'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg'),
        ]);

        // Mock the storage to throw an exception
        Storage::shouldReceive('disk->putFileAs')->andThrow(new \Exception('File upload failed'));

        // Capture the log error
        Log::shouldReceive('error')->once()->with('File storage error: File upload failed');

        $controller = new CourseController();

        $response = $controller->createCourse($request);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertJson('{"error":"File upload failed. Please try again."}');
    }
}