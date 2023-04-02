<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testLogin(): void
    {
        $email = Str::random(10);

        $user = User::factory()->create([
            'email' => "$email@gmail.com",
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/api/v1/auth/login', [
            'email' => $email . '@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        User::where('email', $user->email)->delete();
    }

    public function testLoginWithInvalidCredentials(): void
    {

        $response = $this->post('/api/v1/auth/login', [
            'email' => Str::random(20) . '@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(404);
    }
}
