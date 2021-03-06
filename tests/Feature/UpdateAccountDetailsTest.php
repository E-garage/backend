<?php

namespace Tests\Feature;

use App\Models\UserModel;
use Hash;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertTrue;

class UpdateAccountDetailsTest extends TestCase
{
    private UserModel $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->user); //@phpstan-ignore-line
    }

    public function testNameWasChangedSuccessfully()
    {
        $data = [
            'name' => 'TestTest',
        ];

        $nameBeforeChange = $this->user->name;

        //we want to hit /update/name route with data
        $response = $this->putJson('/api/v1/account/update/name', $data);

        //we want to assert that we've got correct status
        $response->assertOk();

        //we want to assert that name has been changed
        $this->user->refresh();
        assertNotEquals($this->user->name, $nameBeforeChange);

        //we want to assert that name has been changed correctly
        assertEquals($data['name'], $this->user->name);
    }

    public function testEmailWasChangedSuccessfully()
    {
        $data = [
            'email' => 'test@test.com',
        ];

        $emailBeforeChange = $this->user->email;

        //we want to hit /update/email with data
        $response = $this->putJson('/api/v1/account/update/email', $data);

        //we want to assert that we've got correct status
        $response->assertOk();

        //we want to assert that email has been changed
        $this->user->refresh();
        assertNotEquals($this->user->email, $emailBeforeChange);

        //we want to assert that email has been changed correctly
        assertEquals($data['email'], $this->user->email);
    }

    public function testPasswordWasChangedSuccessfully()
    {
        $data = [
            'password' => 'Test1234',
            'password_confirmation' => 'Test1234',
        ];

        $passwordBeforeChange = $this->user->password;

        $response = $this->putJson('/api/v1/account/update/password', $data);
        $response->assertOk();

        $this->user->refresh();
        assertNotEquals($this->user->password, $passwordBeforeChange);

        assertTrue(Hash::check($data['password'], $this->user->password));
    }

    public function testRequestWithInvalidNameWasRejected()
    {
        $nameData = ['name' => 'te'];
        $response = $this->putJson('/api/v1/account/update/name', $nameData);

        $response->assertUnprocessable();
        $response->assertInvalid(['name']);
    }

    public function testRequestWithInvalidEmailWasRejected()
    {
        $emailData = ['email' => 'test'];
        $response = $this->putJson('/api/v1/account/update/email', $emailData);

        $response->assertUnprocessable();
        $response->assertInvalid(['email']);
    }

    public function testRequestWithInvalidPasswordWasRejected()
    {
        $passwordData = ['password' => 'test', 'password_confirmation' => 'test'];
        $response = $this->putJson('/api/v1/account/update/password', $passwordData);

        $response->assertUnprocessable();
        $response->assertInvalid(['password']);
    }
}
