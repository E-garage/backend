<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Family;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdatingInspectionTest extends TestCase
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

    public function testUserCanUpdateOwnedCarsInspection()
    {
        $inspection = $this->car->refresh()->inspection;
        $this->assertEquals(null, $inspection->end_date);

        $response = $this->putJson('/api/v1/cars/inspection/' . $this->car->id . '/update', ['end_date' => '16-01-2022']);
        $response->assertOk();

        $inspection->refresh();
        $this->assertEquals(Carbon::create(2022, 1, 16), $inspection->end_date);
    }

    public function testUserCanUpdateSharedCarsInspection()
    {
        $family = Family::factory()->create();
        $car = Car::factory()->create(['family_id' => $family->id]);
        $family->members()->attach($this->user->id);

        $inspection = $car->refresh()->inspection;
        $this->assertEquals(null, $inspection->end_date);

        $response = $this->putJson('/api/v1/cars/inspection/' . $car->id . '/update', ['end_date' => '16-01-2022']);
        $response->assertOk();

        $inspection->refresh();
        $this->assertEquals(Carbon::create(2022, 1, 16), $inspection->end_date);
    }

    public function testUserCanNotUpdateUnsharedCarsInspection()
    {
        $car = Car::factory()->create();
        $inspection = $car->refresh()->inspection;

        $this->assertEquals(null, $inspection->end_date);

        $response = $this->putJson('/api/v1/cars/inspection/' . $car->id . '/update', ['end_date' => '16-01-2022']);
        $response->assertUnauthorized();

        $inspection->refresh();
        $this->assertEquals(null, $inspection->end_date);
    }
}
