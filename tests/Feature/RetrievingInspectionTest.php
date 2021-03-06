<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Family;
use App\Models\UserModel;
use Tests\TestCase;

class RetrievingInspectionTest extends TestCase
{
    protected UserModel $user;
    protected Car $car;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->user); //@phpstan-ignore-line

        $this->car = Car::factory()->create(['owner_id' => $this->user->id]); //@phpstan-ignore-line
    }

    public function testUserCanRetrieveOwnedCarsInspection()
    {
        $inspection = $this->car->refresh()->inspection;

        $this->assertEquals(null, $inspection->end_date);

        $response = $this->getJson('/api/v1/cars/inspection/' . $this->car->id);
        $response->assertOk();

        $this->assertArrayHasKey('inspection', $response);
        $this->assertSame($inspection->id, $response['inspection']['id']);
    }

    public function testUserCanRetrieveSharedCarsInspection()
    {
        $family = Family::factory()->create();
        $car = Car::factory()->create(['family_id' => $family->id]); //@phpstan-ignore-line
        $inspection = $car->refresh()->inspection; //@phpstan-ignore-line
        $family->members()->attach($this->user->id); //@phpstan-ignore-line

        $response = $this->getJson('/api/v1/cars/inspection/' . $car->id); //@phpstan-ignore-line
        $response->assertOk();

        $this->assertArrayHasKey('inspection', $response);
        $this->assertSame($inspection->id, $response['inspection']['id']);
    }

    public function testUserCanNotRetrieveUnsharedCarsInspection()
    {
        $car = Car::factory()->create();

        $response = $this->getJson('/api/v1/cars/inspection/' . $car->id); //@phpstan-ignore-line
        $response->assertUnauthorized();
    }
}
