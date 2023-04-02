<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    public function testRegister(): void
    {
        $userData = [
            'email' => 'johndoe@gmail.com',
            'password' => 'supersecret',
        ];

        $response = $this->post('/api/v1/auth/register', $userData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);

        User::where('email', $userData['email'])->delete();

    }

    public function testRegisterWithExistingEmail(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'johndoe@example.com',
        ]);

        $userData = [
            'email' => $existingUser->email,
            'password' => 'supersecret',
        ];

        $response = $this->post('/api/v1/auth/register', $userData, ['Accept' => 'application/json']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);

        User::where('email', $existingUser->email)->delete();
    }

}
