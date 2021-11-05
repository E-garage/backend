<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_successfully_registered()
    {
        $data = [
            'name' => 'JohnDoe',
            'email' => 'cool@email.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];

        //we want to hit /signup route with data
        $response = $this->post('api/v1/auth/signup', $data);

        //we want to assert we get proper status
        $response->assertStatus(201);
    }

    public function test_user_registration_data_is_invalid()
    {
        //we want to hit /signup route with empty data
        $response = $this->post('api/v1/auth/signup');

        //we want to assert we get proper status
        $response->assertStatus(400);
    }
}
