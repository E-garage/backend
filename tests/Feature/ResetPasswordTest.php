<?php

namespace Tests\Feature;

use App\Models\UserModel;
use App\Notifications\ResetPasswordNotification;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Notification;
use Password;
use Str;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected UserModel $user;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        $this->user = UserModel::factory()->create(); // @phpstan-ignore-line
    }

    public function testLinkForResettingPasswordHasBeenSent()
    {
        $data = [
            'email' => $this->user['email'],
        ];

        Notification::assertNothingSent();

        $response = $this->putJson('/api/v1/reset-password/send-link', $data);
        $response->assertOk();

        Notification::assertSentTo($this->user, ResetPasswordNotification::class);
    }

    public function testPasswordHasBeenResetedSuccessfully()
    {
        $token = Password::createToken($this->user);
        $newPassword = Str::random();

        $data = [
            'token' => $token,
            'email' => $this->user['email'],
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ];

        $response = $this->putJson('/api/v1/reset-password', $data);
        $response->assertOk();

        $this->assertFalse(Hash::check($newPassword, $this->user['password']));

        $this->user->refresh();

        $this->assertTrue(Hash::check($newPassword, $this->user['password']));
    }

    public function testEmptyRequestWasRejected()
    {
        $data = [];

        Notification::assertNothingSent();

        $response = $this->putJson('/api/v1/reset-password/send-link', $data);
        $response->assertUnprocessable();

        Notification::assertNothingSent();
    }

    public function testRequestWithInvalidEmailWasRejected()
    {
        $data = [
            'email' => 'coolexample.com',
        ];

        Notification::assertNothingSent();

        $response = $this->putJson('/api/v1/reset-password/send-link', $data);
        $response->assertUnprocessable();

        Notification::assertNothingSent();

        $data = [
            'email' => 123456,
        ];

        Notification::assertNothingSent();

        $response = $this->putJson('/api/v1/reset-password/send-link', $data);
        $response->assertUnprocessable();

        Notification::assertNothingSent();
    }
}
