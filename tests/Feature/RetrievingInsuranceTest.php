<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Family;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RetrievingInsuranceTest extends TestCase
{
    use RefreshDatabase;

    protected UserModel $user;
    protected Car $car;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->user); //@phpstan-ignore-line

        $this->car = Car::factory()->create(['owner_id' => $this->user->id]); //@phpstan-ignore-line
    }

    public function testUserCanRetrieveOwnedCarsInsurance()
    {
        $insurance = $this->car->refresh()->insurance;

        $this->assertEquals(null, $insurance->end_date);

        $response = $this->getJson('/api/v1/cars/insurance/' . $this->car->id);
        $response->assertOk();

        $this->assertArrayHasKey('insurance', $response);
        $this->assertSame($insurance->id, $response['insurance']['id']);
    }

    public function testUserCanRetrieveSharedCarsInsurance()
    {
        $family = Family::factory()->create();
        $car = Car::factory()->create(['family_id' => $family->id]); //@phpstan-ignore-line
        $insurance = $car->refresh()->insurance; //@phpstan-ignore-line
        $family->members()->attach($this->user->id); //@phpstan-ignore-line

        $response = $this->getJson('/api/v1/cars/insurance/' . $car->id); //@phpstan-ignore-line
        $response->assertOk();

        $this->assertArrayHasKey('insurance', $response);
        $this->assertSame($insurance->id, $response['insurance']['id']);
    }

    public function testUserCanNotRetrieveUnsharedCarsInsurance()
    {
        $car = Car::factory()->create();

        $response = $this->getJson('/api/v1/cars/insurance/' . $car->id); //@phpstan-ignore-line
        $response->assertUnauthorized();
    }
}
