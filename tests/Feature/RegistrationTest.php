<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testUserWasRegisteredSuccessfully()
    {
        $data = [
            'name' => 'JohnDoe',
            'email' => 'cool@email.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];

        $response = $this->post('api/v1/auth/signup', $data);

        $response->assertCreated();
    }

    public function testEmptyRequestWasRejected()
    {
        $response = $this->postJson('api/v1/auth/signup');

        $response->assertUnprocessable();
    }
}
