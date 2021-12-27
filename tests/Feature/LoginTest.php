<?php

namespace Tests\Feature;

use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testMustEnterEmailAndPassword()
    {
        $this->json('GET', 'api/v1/auth/login')
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ],
            ]);
    }

    public function testSuccessfulLogin()
    {
        $user = UserModel::factory()->create([
            'email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
        ]);

        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];

        $response = $this->json('GET', 'api/v1/auth/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'email',
                ],
            ]);

        $response->assertStatus(200);
    }

    public function testUserLoginDataIsInvalid()
    {
        $response = $this->get('api/v1/auth/login');

        $response->assertStatus(302);
    }
}
