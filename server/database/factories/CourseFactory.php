<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'category_id' => function () {
                return \App\Models\Category::factory()->create()->id;
            },
            'level_id' => function () {
                return \App\Models\Level::factory()->create()->id;
            },
            'tags' => $this->faker->words(3, true),
            'video' => $this->faker->url,
            'thumbnail' => $this->faker->imageUrl()
        ];
    }
}
