<?php

namespace Tests\Feature;

use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function testRegisteredUserHasCorrectRole()
    {
        $data = [
            'name' => 'JohnDoe',
            'email' => 'cool@email.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];

        $this->postJson('api/v1/auth/signup', $data);

        $user = UserModel::where('email', $data['email'])->first();

        $this->assertSame($user['role'], UserModel::USER);
        $this->assertNotSame($user['role'], UserModel::ADMIN);
    }

    public function testUserCanNotSpecifyRoleDuringRegistration()
    {
        $data = [
            'name' => 'JohnDoe',
            'email' => 'cool@email.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role' => UserModel::ADMIN,
        ];

        $this->postJson('api/v1/auth/signup', $data);

        $user = UserModel::where('email', 'cool@email.com')->first();

        $this->assertNotEquals($user['role'], $data['role']);
        $this->assertNotEquals($user['role'], 1);
    }
}
