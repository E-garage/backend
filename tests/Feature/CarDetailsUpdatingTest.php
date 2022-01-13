<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;
use function MongoDB\BSON\toJSON;

class CarDetailsUpdatingTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected UserModel $user;
    protected UserModel $secondUser;

    protected function setUp(): void
    {
        parent::setUp();


        $this->secondUser = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->secondUser); //@phpstan-ignore-line

        $this->addCars(2);

        $this->user = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->user); //@phpstan-ignore-line

        $this->addCars(4);
    }

    public function testUserCanUpdateHisOwnCarDetails()
    {
        $car = $this->user->cars()->first();

        $data = [
            'engine_capacity'=>'3.0l',
            'sits' => '4',
            'horse_power'=>'250',
        ];

        $response = $this->putJson('/api/v1/cars/update/details/' . $car['id'], $data);
        $response->assertOk();

        $carAfterUpdate = Car::find($car['id']);

        $this->assertNotEquals($car['details'], $carAfterUpdate['details']);
        $this->assertEquals($data, $carAfterUpdate['details']);
        $this->assertEquals($car['brand'], $carAfterUpdate['brand']);

    }
    public function testUserCannotUpdateCarsHisNotOwn()
    {
        $car = $this->secondUser->cars()->first();

        $data = [
            'engine_capacity'=>'2,5l',
            'sits' => '5',
            'horse_power'=>'300',
        ];

        $response = $this->putJson('/api/v1/cars/update/details/' . $car['id'], $data);
        $response->assertUnauthorized();
    }


    private function addCars(int $numberOfCars = 1): void
    {
        for ($i = 0; $i < $numberOfCars; $i++) {
            $data = [
                'brand' => $this->faker()->realText(30),
                'description' => $this->faker()->text(50),
                'thumbnail' => UploadedFile::fake()->image('test.png'),
            ];

            $response = $this->postJson('/api/v1/cars/add', $data);
            $response->assertCreated();
        }
    }
}
