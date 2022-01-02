<?php

namespace Tests\Feature;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class CarRetrievingTest extends TestCase
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

    public function testUserGetHisOwnCars()
    {
        $cars = $this->user->cars()->get();
        $cars = $this->getThumbnails($cars);
        $cars = json_decode($cars);

        $response = $this->getJson('/api/v1/cars');
        $response->assertOk();

        $retrievedCars = $response['cars'];

        for ($i = 0; $i < sizeof($retrievedCars); $i++) {
            $expected = $cars[$i];
            $actual = $retrievedCars[$i];

            $this->assertEquals($expected->id, $actual['id']);
            $this->assertEquals($expected->brand, $actual['brand']);
            $this->assertEquals($expected->description, $actual['description']);
            $this->assertEquals($expected->thumbnail, $actual['thumbnail']);
        }
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

    private function getThumbnails(Collection $cars): Collection
    {
        foreach ($cars as $car) {
            $thumbnail = Storage::disk('cars_thumbnails')->get($car['thumbnail']);
            $car['thumbnail'] = base64_encode($thumbnail);
        }

        return $cars;
    }
}
