<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\EstimatedBudget;
use App\Models\Family;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetDeletionTest extends TestCase
{
    use RefreshDatabase;

    protected UserModel $user;
    protected Car $car;
    protected EstimatedBudget $budget;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->user); //@phpstan-ignore-line

        $this->car = Car::factory()->create(['owner_id' => $this->user->id]); //@phpstan-ignore-line
        $this->budget = $this->car->budget; //@phpstan-ignore-line
        $this->budget->update([
            'original_budget' => 400,
            'last_payment_amount' => 300,
            'budget_left' => 100,
        ]);
    }

    public function testUserCanDeleteHisCarBudget()
    {
        $this->assertEquals(400, $this->budget->original_budget);
        $this->assertEquals(300, $this->budget->last_payment_amount);
        $this->assertEquals(100, $this->budget->budget_left);

        $response = $this->deleteJson('/api/v1/car-budget/' . $this->budget->id . '/delete');
        $response->assertOk();

        $this->budget->refresh();

        $this->assertEquals(0, $this->budget->original_budget);
        $this->assertEquals(null, $this->budget->last_payment_amount);
        $this->assertEquals(0, $this->budget->budget_left);
    }

    public function testUserCanDeleteSharedCarsBudget()
    {
        $secondUser = UserModel::factory()->create();
        $family = Family::factory()->create(['owner_id' => $secondUser->id]); //@phpstan-ignore-line
        $this->car->update(['owner_id' => $secondUser->id, 'family_id' => $family->id]); //@phpstan-ignore-line
        $family->members()->attach($this->user->id); //@phpstan-ignore-line

        $this->assertEquals(400, $this->budget->original_budget);
        $this->assertEquals(300, $this->budget->last_payment_amount);
        $this->assertEquals(100, $this->budget->budget_left);

        $response = $this->deleteJson('/api/v1/car-budget/' . $this->budget->id . '/delete');
        $response->assertOk();

        $this->budget->refresh();

        $this->assertEquals(0, $this->budget->original_budget);
        $this->assertEquals(null, $this->budget->last_payment_amount);
        $this->assertEquals(0, $this->budget->budget_left);
    }

    public function testUserCanNotDeleteUnsharedCarsBudget()
    {
        $this->user = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->user); //@phpstan-ignore-line

        $this->assertEquals(400, $this->budget->original_budget);
        $this->assertEquals(300, $this->budget->last_payment_amount);
        $this->assertEquals(100, $this->budget->budget_left);

        $response = $this->getJson('/api/v1/car-budget/' . $this->budget->id);
        $response->assertUnauthorized();
    }
}
