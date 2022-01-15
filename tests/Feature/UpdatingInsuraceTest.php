<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Family;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdatingInsuraceTest extends TestCase
{
    use RefreshDatabase;

    protected UserModel $user;
    protected Car $car;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->user); //@phpstan-ignore-line

        $this->car = Car::factory()->create(['owner_id' => $this->user->id]);
    }

    public function testUserCanUpdateOwnedCarsInsurance()
    {
        $insurance = $this->car->refresh()->insurance;
        $this->assertEquals(null, $insurance->end_date);

        $response = $this->putJson('/api/v1/cars/insurance/' . $this->car->id . '/update', ['end_date' => '16-01-2022']);
        $response->assertOk();

        $insurance->refresh();
        $this->assertEquals(Carbon::create(2022, 1, 16), $insurance->end_date);
    }

    public function testUserCanUpdateSharedCarsInsurance()
    {
        $family = Family::factory()->create();
        $car = Car::factory()->create(['family_id' => $family->id]);
        $family->members()->attach($this->user->id);

        $insurance = $car->refresh()->insurance;
        $this->assertEquals(null, $insurance->end_date);

        $response = $this->putJson('/api/v1/cars/insurance/' . $car->id . '/update', ['end_date' => '16-01-2022']);
        $response->assertOk();
        
        $insurance->refresh();
        $this->assertEquals(Carbon::create(2022, 1, 16), $insurance->end_date);
    }

    public function testUserCanNotUpdateUnsharedCarsInsurance()
    {
        $car = Car::factory()->create();
        $insurance = $car->refresh()->insurance;

        $this->assertEquals(null, $insurance->end_date);

        $response = $this->putJson('/api/v1/cars/insurance/' . $car->id . '/update', ['end_date' => '16-01-2022']);
        $response->assertUnauthorized();

        $insurance->refresh();
        $this->assertEquals(null, $insurance->end_date);
    }
}
