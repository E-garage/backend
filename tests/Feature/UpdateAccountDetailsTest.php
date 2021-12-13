<?php

namespace Tests\Feature;

use App\Models\UserModel;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertTrue;

class UpdateAccountDetailsTest extends TestCase
{
    private UserModel $user;

    public function testNameChangedSuccessfully()
    {
        $this->user = new UserModel();
        $this->actingAs($this->user);

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


    public function testEmailChangedSuccessfully()
    {
        $this->user = new UserModel();
        $this->actingAs($this->user);

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

    public function testPasswordChangedSuccessfully()
    {
        $this->user = new UserModel();
        $this->actingAs($this->user);

        $data = [
            'password' => 'Test1234',
            'password_confirmation' => 'Test1234',
        ];

        $passwordBeforeChange = $this->user->password;

        //we want to hit /update/password with data
        $response = $this->putJson('/api/v1/account/update/password', $data);

        //we want to assert that we've got correct status
        $response->assertOk();

        //we want to assert that password has been changed
        $this->user->refresh();
        assertNotEquals($this->user->password, $passwordBeforeChange);

        //we want to assert that password has been changed correctly
        assertTrue(Hash::check($data['password'], $this->user->password));
    }

    public function testValidationExceptionsOccurs()
    {
        $this->user = new UserModel();
        $this->actingAs($this->user);

        $nameData = ['name' => 'te'];
        $emailData = ['email' => 'test'];
        $passwordData = ['password' => 'test', 'password_confirmation' => 'test'];

        //we want to hit /update/name route with nameData
        $response = $this->putJson('/api/v1/account/update/name', $nameData);

        //we want to assert that validation exception occured
        $response->assertUnprocessable();
        $response->assertInvalid(['name']);

        //we want to hit /update/email route with emailData
        $response = $this->putJson('/api/v1/account/update/email', $emailData);

        //we want to assert that validation exception occured
        $response->assertUnprocessable();
        $response->assertInvalid(['email']);

        //we want to hit /update/password route with passwordData
        $response = $this->putJson('/api/v1/account/update/password', $passwordData);

        //we want to assert that validation exception occured
        $response->assertUnprocessable();
        $response->assertInvalid(['password']);
    }
}
