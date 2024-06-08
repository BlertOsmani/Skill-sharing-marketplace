<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use App\Services\Services;
use Illuminate\Validation\ValidationException;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_success()
    {
        $user = User::factory()->create([
            'username' => 'johndoe',
            'password' => Hash::make('password'.'randomsalt'),
            'password_salt' => 'randomsalt',
        ]);

        $response = $this->postJson('/api/auth/user/login', [
            'username' => 'johndoe',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token', 'token_type', 'expires_in', 'user']);
    }

    public function test_login_invalid_credentials()
    {
        $user = User::factory()->create([
            'username' => 'johndoe',
            'password' => Hash::make('password'.'randomsalt'),
            'password_salt' => 'randomsalt',
        ]);

        $response = $this->postJson('/api/auth/user/login', [
            'username' => 'johndoe',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['error' => 'Incorrect password for user: johndoe']);
    }

    public function test_login_user_not_found()
    {
        $response = $this->postJson('/api/auth/user/login', [
            'username' => 'nonexistentuser',
            'password' => 'password',
        ]);

        $response->assertStatus(404)
                 ->assertJson(['error' => 'User not found with username: nonexistentuser']);
    }

    public function test_logout_success()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->postJson('/api/auth/user/logout', [], ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['message']);
    }
}
