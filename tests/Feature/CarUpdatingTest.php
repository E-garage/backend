<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class CarUpdatingTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected UserModel $user;
    protected UserModel $secondUser;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('cars_thumbnails');

        $this->secondUser = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->secondUser); //@phpstan-ignore-line

        $this->addCars(2);

        $this->user = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->user); //@phpstan-ignore-line

        $this->addCars(4);
    }

    public function testUserCanUpdateHisOwnCar()
    {
        $car = $this->user->cars()->first();
        $file = UploadedFile::fake()->image('updatedImg.png');

        $data = [
            'brand' => 'Updated brand',
            'description' => 'Updated description',
            'thumbnail' => $file,
        ];

        $response = $this->putJson('/api/v1/cars/update/'. $car['id'], $data);
        $response->assertOk();

        $carAfterUpdate = Car::find($car['id']);

        $this->assertNotEquals($car['brand'], $carAfterUpdate['brand']);
        $this->assertNotEquals($car['description'], $carAfterUpdate['description']);
        $this->assertNotEquals($car['thumbnail'], $carAfterUpdate['thumbnail']);

        $this->assertEquals($data['brand'], $carAfterUpdate['brand']);
        $this->assertEquals($data['description'], $carAfterUpdate['description']);

        Storage::disk('cars_thumbnails')->assertMissing($car['thumbnail']);
        Storage::disk('cars_thumbnails')->assertExists($carAfterUpdate['thumbnail']);
    }

    public function testUserCannotUpdateCarsHisNotOwn()
    {
        $car = $this->secondUser->cars()->first();
        $file = UploadedFile::fake()->image('updatedImg.png');

        $data = [
            'brand' => 'Updated brand',
            'description' => 'Updated description',
            'thumbnail' => $file,
        ];

        $response = $this->putJson('/api/v1/cars/update/'. $car['id'], $data);
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
