<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Storage;
use Tests\TestCase;

class RefuelingRetrievingTest extends TestCase
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
        $this->seconduser = $car->owner; //@phpstan-ignore-line
        $this->actingAs($this->seconduser);
        $this->addRefueling(3);
    }

    public function testUserCanGetOnlyHisOwnRefueling()
    {
        $refueling = $this->user->refueling()->get();
        $refueling = json_decode($refueling);

        $response = $this->getJson('/api/v1/refueling');
        $response->assertOk();

        $retrievedRefueling = $response['refueling'];

        for ($i = 0; $i < sizeof($retrievedRefueling); $i++) {
            $expected = $refueling[$i];
            $actual = $retrievedRefueling[$i];

            $this->assertEquals($expected->id, $actual['id']);
            $this->assertEquals($expected->date, $actual['date']);
            $this->assertEquals($expected->FuelType, $actual['FuelType']);
            $this->assertEquals($expected->amount, $actual['amount']);
            $this->assertEquals($expected->TotalPrice, $actual['TotalPrice']);
        }
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
