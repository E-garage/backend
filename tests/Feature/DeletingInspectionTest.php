<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Family;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletingInspectionTest extends TestCase
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

    public function testUserCanDeleteOwnedCarsInspection()
    {
        $inspection = $this->car->refresh()->inspection;
        $inspection->update(['end_date' => '16-01-2022']);
        $inspection->refresh();

        $this->assertEquals(Carbon::create(2022, 1, 16), $inspection->end_date);

        $response = $this->deleteJson('/api/v1/cars/inspection/' . $this->car->id . '/delete');
        $response->assertOk();

        $inspection->refresh();
        $this->assertEquals(null, $inspection->end_date);
    }

    public function testUserCanDeleteSharedCarsInspection()
    {
        $family = Family::factory()->create();
        $car = Car::factory()->create(['family_id' => $family->id]); //@phpstan-ignore-line
        $family->members()->attach($this->user->id); //@phpstan-ignore-line

        $inspection = $car->refresh()->inspection; //@phpstan-ignore-line
        $inspection->update(['end_date' => '16-01-2022']);

        $this->assertEquals(Carbon::create(2022, 1, 16), $inspection->end_date);

        $response = $this->deleteJson('/api/v1/cars/inspection/' . $car->id . '/delete'); //@phpstan-ignore-line
        $response->assertUnauthorized();

        $inspection->refresh();
        $this->assertEquals(Carbon::create(2022, 1, 16), $inspection->end_date);
    }

    public function testUserCanNotDeleteUnsharedCarsInspection()
    {
        $car = Car::factory()->create();
        $inspection = $car->refresh()->inspection; //@phpstan-ignore-line
        $inspection->update(['end_date' => '16-01-2022']);
        $inspection->refresh();

        $this->assertEquals(Carbon::create(2022, 1, 16), $inspection->end_date);

        $response = $this->deleteJson('/api/v1/cars/inspection/' . $car->id . '/delete'); //@phpstan-ignore-line
        $response->assertUnauthorized();

        $inspection->refresh();
        $this->assertEquals(Carbon::create(2022, 1, 16), $inspection->end_date);
    }
}
