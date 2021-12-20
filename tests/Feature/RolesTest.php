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
        $user = UserModel::factory()->create();
        $role = $user->role; //@phpstan-ignore-line

        $this->assertSame($user['role_id'], $role['id']);
        $this->assertSame(2, $user['role_id']);
        $this->assertSame(2, $role['id']);
        $this->assertSame('user', $role['type']);
    }

    public function testUserCanNotSpecifyRoleDuringRegistration()
    {
        $data = [
            'name' => 'JohnDoe',
            'email' => 'cool@email.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role_id' => 1,
        ];

        $response = $this->postJson('api/v1/auth/signup', $data);
        $response->assertStatus(201);

        $user = UserModel::where('email', 'cool@email.com')->first();
        $this->assertEquals(2, $user->role_id);
    }
}
