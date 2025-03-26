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

     /** @test */
    #[Test]
    public function it_registers_a_professor_successfully()
    {
        $data = [
            'firstname' => 'Paul',
            'lastname' => 'Doe',
            'sex' => "M",
            'email' => 'paul@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'professor',
            'grade' => 'Doctor'
        ];

        // Envoi de la requête POST à la route d'inscription de professeur
        $response = $this->postJson('/api/register/professor', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id', 'firstname', 'lastname', 'sex', 'role', 'grade', 'email', 'created_at', 'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'paul@example.com'
        ]);
    }

    /** @test */
    #[Test]
    public function it_registers_an_administrator_successfully()
    {
        $data = [
            'firstname' => 'Admin',
            'lastname' => 'User',
            'sex' => "M",
            'email' => 'admin@example.com',
            'password' => 'adminpassword',
            'role' => 'administrator',
            'password_confirmation' => 'adminpassword'
        ];

        // Envoi de la requête POST à la route d'inscription de l'administrateur
        $response = $this->postJson('/api/register/administrator', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id', 'firstname', 'lastname', 'sex', 'email', 'role', 'created_at', 'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com'
        ]);
    }

    /** @test */
    #[Test]
    public function it_logs_in_a_professor_successfully()
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

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id', 'firstname', 'lastname', 'sex', 'email', 'created_at', 'updated_at'
                ],
                'token'
            ]);

        $this->assertNotEmpty($response['token']);
    }

    /** @test */
    #[Test]
    public function it_logs_in_an_administrator_successfully()
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpassword'),
        ]);

        $data = [
            'email' => 'admin@example.com',
            'password' => 'adminpassword'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id', 'firstname', 'lastname', 'sex', 'email', 'created_at', 'updated_at'
                ],
                'token'
            ]);

        $this->assertNotEmpty($response['token']);
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

        $response->assertStatus(401)
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

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
