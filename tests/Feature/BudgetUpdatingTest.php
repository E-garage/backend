<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Family;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetUpdatingTest extends TestCase
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

    public function testUserCanUpdateHisCarBudget()
    {
        $budget = $this->car->budget;

        $this->assertEquals(0, $budget->original_budget);

        $response = $this->putJson('/api/v1/car-budget/' . $this->car->id . '/update/original-budget', ['original_budget' => 500]);
        $response->assertOk();

        $budget->refresh();
        $this->assertEquals(500, $budget->original_budget);
        $this->assertEquals(0, $budget->last_payment_amount);

        $response = $this->putJson('/api/v1/car-budget/' . $this->car->id . '/update/last-payment', ['last_payment_amount' => 500]);
        $response->assertOk();

        $budget->refresh();
        $this->assertEquals(500, $budget->last_payment_amount);
    }

    public function testUserCanUpdateSharedCarsBudget()
    {
        $secondUser = UserModel::factory()->create();
        $family = Family::factory()->create(['owner_id' => $secondUser->id]); //@phpstan-ignore-line
        $car = Car::factory()->create(['owner_id' => $secondUser->id, 'family_id' => $family->id]); //@phpstan-ignore-line
        $family->members()->attach($this->user->id); //@phpstan-ignore-line
        $budget = $car->budget; //@phpstan-ignore-line

        $this->assertEquals(0, $budget->original_budget);

        $response = $this->putJson('/api/v1/car-budget/' . $car->id . '/update/original-budget', ['original_budget' => 500]); //@phpstan-ignore-line
        $response->assertOk();

        $budget->refresh();
        $this->assertEquals(500, $budget->original_budget);
        $this->assertEquals(0, $budget->last_payment_amount);

        $response = $this->putJson('/api/v1/car-budget/' . $car->id . '/update/last-payment', ['last_payment_amount' => 500]); //@phpstan-ignore-line
        $response->assertOk();

        $budget->refresh();
        $this->assertEquals(500, $budget->last_payment_amount);
    }

    public function testUserCanNotUpdateUnsharedCarsBudget()
    {
        $car = Car::factory()->create();
        $budget = $car->budget; //@phpstan-ignore-line

        $this->assertEquals(0, $budget->original_budget);
        $this->assertEquals(0, $budget->last_payment_amount);

        $response = $this->putJson('/api/v1/car-budget/' . $car->id . '/update/original-budget', ['original_budget' => 500]); //@phpstan-ignore-line
        $response->assertUnauthorized();

        $response = $this->putJson('/api/v1/car-budget/' . $car->id . '/update/last-payment', ['last_payment_amount' => 500]); //@phpstan-ignore-line
        $response->assertUnauthorized();

        $this->assertEquals(0, $budget->original_budget);
        $this->assertEquals(0, $budget->last_payment_amount);
    }
}
