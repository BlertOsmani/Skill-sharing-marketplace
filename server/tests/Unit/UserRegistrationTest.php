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

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_success()
    {
        $servicesMock = Mockery::mock(Services::class);
        $servicesMock->shouldReceive('requestAndValidateFields_SignUp')->andReturn([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'johndoe@example.com',
            'username' => 'johndoe',
            'password' => 'password',
        ]);
        $servicesMock->shouldReceive('userexists_SignUp')->andReturn(false);
        $servicesMock->shouldReceive('generateSalt')->andReturn('randomsalt');

        $this->app->instance(Services::class, $servicesMock);

        $response = $this->postJson('/api/auth/user/create', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'johndoe@example.com',
            'username' => 'johndoe',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => 'User registered successfully']);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
    }

    public function test_register_user_exists()
    {
        User::factory()->create(['email' => 'johndoe@example.com']);

        $servicesMock = Mockery::mock(Services::class);
        $servicesMock->shouldReceive('requestAndValidateFields_SignUp')->andReturn([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'johndoe@example.com',
            'username' => 'johndoe',
            'password' => 'password',
        ]);
        $servicesMock->shouldReceive('userexists_SignUp')->andReturn(true);

        $this->app->instance(Services::class, $servicesMock);

        $response = $this->postJson('/api/auth/user/create', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'johndoe@example.com',
            'username' => 'johndoe',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'User already exists']);
    }

    public function test_register_validation_error()
    {
        $servicesMock = Mockery::mock(Services::class);
        
        $servicesMock->shouldReceive('requestAndValidateFields_SignUp')->andThrow(
            new ValidationException(
                Validator::make([], []), 
                response()->json(['errors' => ['email' => ['The email field is required.']]])
            )
        );

        $this->app->instance(Services::class, $servicesMock);

        $response = $this->postJson('/api/auth/user/create', [
            'firstName' => '',
            'lastName' => '',
            'email' => '',
            'username' => '',
            'password' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }

}
