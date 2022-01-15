<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Refueling;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Storage;
use Tests\TestCase;

class RefuelingUpdatingTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected UserModel $user;
    protected UserModel $secondUser;
    private $car;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('refueling_receipt');

        $car = Car::factory()->create();
        $this->car = $car;
        $this->user = $car->owner; //@phpstan-ignore-line
        $this->actingAs($this->user);
        $this->addRefueling(2);

        $car = Car::factory()->create();
        $this->car = $car;
        $this->secondUser = $car->owner; //@phpstan-ignore-line
        $this->actingAs($this->secondUser);
        $this->addRefueling(3);

        $this->actingAs($this->user);
    }

    public function testUserCanUpdateHisOwnRefueling()
    {
        $refueling = $this->user->refueling()->first();

        $data = [
            'date' => '10/10/20',
            'FuelType' => 'Updated',
            'amount' => '5',
            'TotalPrice' => '15',
        ];

        $response = $this->putJson('/api/v1/refueling/update/' . $refueling['id'], $data);
        $response->assertOk();

        $refuelingAfterUpdate = Refueling::find($refueling['id']);

        $this->assertNotEquals($refueling['FuelType'], $refuelingAfterUpdate['FuelType']);
        $this->assertNotEquals($refueling['amount'], $refuelingAfterUpdate['amount']);
        $this->assertNotEquals($refueling['TotalPrice'], $refuelingAfterUpdate['TotalPrice']);

        $this->assertEquals($data['FuelType'], $refuelingAfterUpdate['FuelType']);
        $this->assertEquals($data['amount'], $refuelingAfterUpdate['amount']);
        $this->assertEquals($data['TotalPrice'], $refuelingAfterUpdate['TotalPrice']);
    }

    public function testUserCannotUpdateCarsHisNotOwn()
    {
        $refueling = $this->secondUser->refueling()->first();

        $data = [
            'date' => '10/10/20',
            'FuelType' => 'Updated',
            'amount' => '5',
            'TotalPrice' => '15',
        ];

        $response = $this->putJson('/api/v1/refueling/update/' . $refueling['id'], $data);
        $response->assertUnauthorized();
    }

    private function addRefueling(int $numberOfRefueling = 1): void
    {
        for ($i = 0; $i < $numberOfRefueling; $i++) {
            $data = [
                'car_id' => $this->car->id,
                'date' => $this->faker->date('d/m/y', 'strtotime'),
                'FuelType' => 'gasoline',
                'amount' => $this->faker->text(10),
                'TotalPrice' => $this->faker->text(8),
            ];

            $response = $this->postJson('/api/v1/refueling/add', $data);
            $response->assertCreated();
        }
    }
}
