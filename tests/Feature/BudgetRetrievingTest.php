<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Family;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetRetrievingTest extends TestCase
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

    public function testUserCanRetrieveHisCarBudget()
    {
        $budget = $this->car->budget;

        $this->assertEquals(0, $budget['original_budget']);
        $this->assertEquals(null, $budget['last_payment_amount']);
        $this->assertEquals(0, $budget['budget_left']);

        $response = $this->getJson('/api/v1/car-budget/' . $budget->id);
        $response->assertOk();

        $this->assertEquals($budget['id'], $response['budget']['id']);
        $budget = $response['budget'];

        $this->assertEquals(0, $budget['original_budget']);
        $this->assertEquals(null, $budget['last_payment_amount']);
        $this->assertEquals(0, $budget['budget_left']);
    }

    public function testUserCanRetrieveSharedCarsBudget()
    {
        $secondUser = UserModel::factory()->create();
        $family = Family::factory()->create(['owner_id' => $secondUser->id]); //@phpstan-ignore-line
        $car = Car::factory()->create(['owner_id' => $secondUser->id, 'family_id' => $family->id]); //@phpstan-ignore-line
        $family->members()->attach($this->user->id); //@phpstan-ignore-line
        $budget = $car->budget; //@phpstan-ignore-line

        $this->assertEquals(0, $budget['original_budget']);
        $this->assertEquals(null, $budget['last_payment_amount']);
        $this->assertEquals(0, $budget['budget_left']);

        $response = $this->getJson('/api/v1/car-budget/' . $budget->id);
        $response->assertOk();

        $this->assertEquals($budget['id'], $response['budget']['id']);

        $budget = $response['budget'];

        $this->assertEquals(0, $budget['original_budget']);
        $this->assertEquals(null, $budget['last_payment_amount']);
        $this->assertEquals(0, $budget['budget_left']);
    }

    public function testUserCanNotRetrieveUnsharedCarsBudget()
    {
        $car = Car::factory()->create();
        $budget = $car->budget; //@phpstan-ignore-line

        $response = $this->getJson('/api/v1/car-budget/' . $budget->id);
        $response->assertUnauthorized();
    }
}
