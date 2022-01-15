<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Refueling;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Storage;
use Tests\TestCase;

class RefuelingDeletionTest extends TestCase
{
    use RefreshDatabase;

    protected UserModel $user;
    protected UserModel $otherUser;
    protected $car;
    protected $id;

    protected function setUp(): void
    {
        parent::setUp();

        $car = Car::factory()->create();
        $this->car = $car;
        $this->user = $car->owner; //@phpstan-ignore-line
        $this->actingAs($this->user);

        Storage::fake('refueling_receipt');
    }

    public function testRefuelingHasBeenDeletedSuccessfully()
    {
        $data = [
            'car_id' => $this->car->id,
            'date' => '10/10/2020',
            'FuelType' => 'gasoline',
            'amount' => '50',
            'TotalPrice' => '150',
        ];

        $response = $this->postJson('/api/v1/refueling/add', $data);
        $response->assertCreated();

        $refueling = Refueling::where('owner_id', $this->user->id)->first();
        $this->assertDatabaseHas('refuelings', $refueling->toArray());

        $response = $this->deleteJson('/api/v1/refueling/delete/' . $refueling->id);
        $response->assertOk();

        $this->assertDatabaseMissing('refuelings', $refueling->toArray());
    }

    public function testOnlyOwnerCanDeleteHisRefueling()
    {
        $data = [
            'car_id' => $this->car->id,
            'date' => '10/10/2020',
            'FuelType' => 'gasoline',
            'amount' => '50',
            'TotalPrice' => '150',
        ];

        $response = $this->postJson('/api/v1/refueling/add', $data);
        $response->assertCreated();

        $refueling = Refueling::where('owner_id', $this->user->id)->first();

        $this->otherUser = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->otherUser); //@phpstan-ignore-line

        $response = $this->deleteJson('/api/v1/refueling/delete/' . $refueling->id);
        $response->assertUnauthorized();

        $this->actingAs($this->user);

        $response = $this->deleteJson('/api/v1/refueling/delete/' . $refueling->id);
        $response->assertOk();
    }
}
