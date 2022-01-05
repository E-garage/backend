<?php

namespace Tests\Feature;

use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LastParkedLocationTest extends TestCase
{
    use RefreshDatabase;

    protected UserModel $user;
    protected array $coordinates;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->user); //@phpstan-ignore-line

        $this->coordinates = [
            'longitude' => '+15.123456',
            'latitude' => '-25.122422',
        ];
    }

    public function testEmptyRequestIsRejected()
    {
        $response = $this->postJson('/api/v1/last-parked-location/set', []);
        $response->assertUnprocessable();
    }

    public function testRequestWithInvalidDataIsRejected()
    {
        $coordinates = [
            'longitude' => 'longItude',
            'latitude' => 'latItude',
        ];

        $response = $this->postJson('/api/v1/last-parked-location/set', $coordinates);
        $response->assertUnprocessable();

        $coordinates = [
            'longitude' => 15.123456,
            'latitude' => 25.122422,
        ];

        $response = $this->postJson('/api/v1/last-parked-location/set', $coordinates);
        $response->assertUnprocessable();
    }

    public function testUserCanSetLocationSuccessfully()
    {
        $response = $this->postJson('/api/v1/last-parked-location/set', $this->coordinates);
        $response->assertOk();

        $location = $this->user->location()->first();

        $this->assertEquals('+15.123456', $location['longitude']);
        $this->assertEquals('-25.122422', $location['latitude']);
    }

    public function testUserCanGetLocationSuccessfully()
    {
        $response = $this->postJson('/api/v1/last-parked-location/set', $this->coordinates);
        $response->assertOk();

        $location = $this->user->location()->first();

        $this->assertEquals('+15.123456', $location['longitude']);
        $this->assertEquals('-25.122422', $location['latitude']);

        $response = $this->getJson('/api/v1/last-parked-location/');
        $response->assertOk();

        $this->assertEquals('+15.123456', $response['longitude']);
        $this->assertEquals('-25.122422', $response['latitude']);
    }

    public function testUserCanUpdateLocationSuccessfully()
    {
        $response = $this->postJson('/api/v1/last-parked-location/set', $this->coordinates);
        $response->assertOk();

        $location = $this->user->location()->first();

        $this->assertEquals('+15.123456', $location['longitude']);
        $this->assertEquals('-25.122422', $location['latitude']);

        $coordinates = [
            'longitude' => '+55.555555',
            'latitude' => '-55.555555',
        ];

        $response = $this->postJson('/api/v1/last-parked-location/set', $coordinates);
        $response->assertOk();

        $location->refresh();

        $this->assertEquals('+55.555555', $location['longitude']);
        $this->assertEquals('-55.555555', $location['latitude']);
    }

    public function testUserCanDeleteLocationSuccessfully()
    {
        $response = $this->postJson('/api/v1/last-parked-location/set', $this->coordinates);
        $response->assertOk();

        $location = $this->user->location()->first();

        $this->assertEquals('+15.123456', $location['longitude']);
        $this->assertEquals('-25.122422', $location['latitude']);

        $response = $this->deleteJson('/api/v1/last-parked-location/delete');
        $response->assertOk();

        $location->refresh();

        $this->assertNull($location['longitude']);
        $this->assertNull($location['latitude']);
    }
}
