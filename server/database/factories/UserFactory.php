<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->unique()->userName,
            'password' => bcrypt('password'), // or Hash::make('password')
            'password_salt' => Str::random(10),
            'verification_token' => Str::random(60),
            'verification_status' => $this->faker->randomElement(['pending', 'verified']),
            'auth_token' => Str::random(6),
            'auth_enabled' => $this->faker->boolean,
            'bio' => $this->faker->text(250),
            'profile_picture' => $this->faker->imageUrl(200, 200, 'people', true, 'Faker'),
            'is_active' => $this->faker->randomElement(['yes', 'no']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
