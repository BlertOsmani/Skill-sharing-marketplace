<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Level;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrolledCoursesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to get enrolled courses for a user with no courses.
     *
     * @return void
     */
    public function test_get_enrolled_courses_no_courses()
    {
        $user = User::factory()->create();

        $response = $this->json('GET', '/api/course/enrolled', ['userId' => $user->id]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'No courses found.']);
    }

    /**
     * Test to get enrolled courses for a user with courses.
     *
     * @return void
     */
    public function test_get_enrolled_courses_with_courses()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $level = Level::factory()->create();
        $author = User::factory()->create();

        $course = Course::factory()->create([
            'category_id' => $category->id,
            'level_id' => $level->id,
            'user_id' => $author->id,
        ]);

        Enrollment::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);

        $response = $this->json('GET', '/api/course/enrolled', ['userId' => $user->id]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'course_id',
                         'course_author',
                         'course_title',
                         'category_name',
                         'level_name',
                         'course_tags',
                         'course_thumbnail',
                         'enrollments_count',
                     ],
                 ]);
    }

    /**
     * Test to get enrolled courses with a limit.
     *
     * @return void
     */
    public function test_get_enrolled_courses_with_limit()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $level = Level::factory()->create();
        $author = User::factory()->create();

        $courses = Course::factory(5)->create([
            'category_id' => $category->id,
            'level_id' => $level->id,
            'user_id' => $author->id,
        ]);

        foreach ($courses as $course) {
            Enrollment::factory()->create([
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]);
        }

        $response = $this->json('GET', '/api/course/enrolled', [
            'userId' => $user->id,
            'limit' => 2,
        ]);

        $response->assertStatus(200)
                 ->assertJsonCount(2);
    }
}