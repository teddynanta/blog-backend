<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_register()
    {
        Role::factory(2)->create();
        $response = $this->post('/api/register', [
            'name' => 'User Test',
            'email' => 'user99@test.com',
            'password' => 'password',
            'role_id' => 1,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'avatar'
                ]
            ]);
    }

    public function test_user_cannot_register()
    {
        Role::factory(2)->create();
        $response = $this->post('/api/register', [
            'name' => 'User Test',
            'email' => 'user99',
            'password' => 'pass',
            'role_id' => 100,
        ]);
        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => [],
            ]);
    }

    public function test_user_can_login()
    {
        $this->seed();
        $response = $this->post('/api/login', [
            'email' => User::first()->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'token',
                ],
            ]);
    }

    public function test_user_cannot_login_invalid_creds()
    {
        $this->seed();
        $response = $this->post('/api/login', [
            'email' => 'apaitu@email.com',
            'password' => 'pas',
        ]);
        Log::info(($response->json()));
        $response->assertStatus(401)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => [],
            ]);
    }

    public function test_user_cannot_login_validation_error()
    {
        $this->seed();
        $response = $this->post('/api/login', [
            'email' => 'error',
            'password' => 'pas',
        ]);
        Log::info(($response->json()));
        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => [],
            ]);
    }

    public function test_user_already_logged_in()
    {
        $this->seed();
        $response = $this->post('/api/login', [
            'email' => 'error',
            'password' => 'pas',
        ]);
        Log::info(($response->json()));
        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => [],
            ]);
    }
}
