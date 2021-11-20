<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserSuccessfullyLogin()
    {
        $data = [
            'email' => 'cool@email.com',
            'password' => '12345678',
        ];

        //we want to hit /login route with data
        $response = $this->get('api/v1/auth/login', $data);

        //we want to assert we get proper status
        $response->assertStatus(201);
    }

    public function testUserLoginDataIsInvalid()
    {
        $response = $this->get('api/v1/auth/login');

        $response->assertStatus(302);
    }
}
