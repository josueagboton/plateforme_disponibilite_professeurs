<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use PHPUnit\Util\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    /** @test */
    use RefreshDatabase;

    #[Test]
    public function it_registers_a_user_successfully()
    {
        $data = [
            'firstname' => 'paul',
            'lastname' => 'Doe',
            'sex' => "M",
            'email' => 'paul@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        // Envoi de la requête POST à la route d'inscription
        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201) // Vérifie le statut HTTP 201
            ->assertJsonStructure([
                'user' => [
                    'id', 'firstname','lastname','sex', 'email', 'created_at', 'updated_at'
                ],
                'token'
            ]);

        // Vérifie que l'utilisateur est enregistré dans la base de données
        $this->assertDatabaseHas('users', [
            'email' => 'paul@example.com'
        ]);
    }

    /** @test */
     /** @test */
    #[Test]
    public function it_logs_in_a_user_successfully()
    {
        $user = User::factory()->create([
            'email' => 'paul@example.com',
            'password' => bcrypt('password123'),
        ]);

        $data = [
            'email' => 'paul@example.com',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(200) // Vérifie le statut HTTP 200
            ->assertJsonStructure([
                'user' => [
                    'id', 'firstname', 'lastname','sex', 'email', 'created_at', 'updated_at'
                ],
                'token'
            ]);
    }

    /** @test */
    #[Test]
    public function it_fails_login_with_wrong_credentials()
    {
        $user = User::factory()->create([
            'email' => 'paul@example.com',
            'password' => bcrypt('password123'),
        ]);

        $data = [
            'email' => 'paul@example.com',
            'password' => 'wrongpassword'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(401) // Vérifie le statut HTTP 401 (Unauthorized)
            ->assertJson([
                'message' => 'Invalid credentials'
            ]);
    }

    /** @test */
    #[Test]
    public function it_requires_email_and_password_for_login()
    {
        $data = [];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(422) // Vérifie le statut HTTP 422 (Unprocessable Entity)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
