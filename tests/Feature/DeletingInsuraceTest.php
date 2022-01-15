<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Family;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletingInsuraceTest extends TestCase
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

    public function testUserCanDeleteOwnedCarsInsurance()
    {
        $insurance = $this->car->refresh()->insurance;
        $insurance->update(['end_date' => '16-01-2022']);
        $insurance->refresh();

        $this->assertEquals(Carbon::create(2022, 1, 16), $insurance->end_date);

        $response = $this->deleteJson('/api/v1/cars/insurance/' . $this->car->id . '/delete');
        $response->assertOk();

        $insurance->refresh();
        $this->assertEquals(null, $insurance->end_date);
    }

    public function testUserCanNotDeleteSharedCarsInsurance()
    {
        $family = Family::factory()->create();
        $car = Car::factory()->create(['family_id' => $family->id]); //@phpstan-ignore-line
        $family->members()->attach($this->user->id); //@phpstan-ignore-line

        $insurance = $car->refresh()->insurance; //@phpstan-ignore-line
        $insurance->update(['end_date' => '16-01-2022']);

        $this->assertEquals(Carbon::create(2022, 1, 16), $insurance->end_date);

        $response = $this->deleteJson('/api/v1/cars/insurance/' . $car->id . '/delete'); //@phpstan-ignore-line
        $response->assertUnauthorized();

        $insurance->refresh();
        $this->assertEquals(Carbon::create(2022, 1, 16), $insurance->end_date);
    }

    public function testUserCanNotDeleteUnsharedCarsInsurance()
    {
        $car = Car::factory()->create();
        $insurance = $car->refresh()->insurance; //@phpstan-ignore-line
        $insurance->update(['end_date' => '16-01-2022']);
        $insurance->refresh();

        $this->assertEquals(Carbon::create(2022, 1, 16), $insurance->end_date);

        $response = $this->deleteJson('/api/v1/cars/insurance/' . $car->id . '/delete'); //@phpstan-ignore-line
        $response->assertUnauthorized();

        $this->assertTrue($insurance->end_date->equalTo(Carbon::create(2022, 1, 16)));
    }
}
