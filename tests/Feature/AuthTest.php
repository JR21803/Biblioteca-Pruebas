<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'usuario@test.com',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'usuario@test.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'user'
            ]);
    }

    public function test_login_fails_with_wrong_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401);
    }

        public function test_login_fails_with_wrong_email()
    {
        $user = User::factory()->create([
            'email' => 'usuario@test.com',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'wrong@email.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(401);
    }

    public function test_profile_requires_authentication()
    {
        $response = $this->getJson('/api/v1/profile');

        $response->assertStatus(401);
    }
}