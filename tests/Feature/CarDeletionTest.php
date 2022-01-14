<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class CarDeletionTest extends TestCase
{
    use RefreshDatabase;

    protected UserModel $user;
    protected UserModel $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('cars_thumbnails');

        $this->user = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->user); //@phpstan-ignore-line
    }

    public function testCarHasBeenDeletedSuccessfully()
    {
        $data = [
            'brand' => 'BMW X12',
            'description' => 'Testing description',
            'thumbnail' => UploadedFile::fake()->image('fake.png'),
        ];

        $response = $this->postJson('/api/v1/cars/add', $data);
        $response->assertCreated();

        $car = Car::where('owner_id', $this->user->id)->first();
        $this->assertDatabaseHas('cars', $car->toArray());

        $budget = $car->budget()->get();
        $this->assertDatabaseHas('estimated_budget', $budget[0]->toArray());

        $response = $this->deleteJson('/api/v1/cars/delete/' . $car->id);
        $response->assertOk();

        $this->assertDatabaseMissing('cars', $car->toArray());
        $this->assertDatabaseMissing('estimated_budget', $budget[0]->toArray());
    }

    public function testOnlyOwnerCanDeleteHisCar()
    {
        $data = [
            'brand' => 'BMW X12',
            'description' => 'Testing description',
            'thumbnail' => UploadedFile::fake()->image('fake.png'),
        ];

        $response = $this->postJson('/api/v1/cars/add', $data);
        $response->assertCreated();

        $car = Car::where('owner_id', $this->user->id)->first();

        $this->otherUser = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->otherUser); //@phpstan-ignore-line

        $response = $this->deleteJson('/api/v1/cars/delete/' . $car->id);
        $response->assertUnauthorized();

        $this->actingAs($this->user);

        $response = $this->deleteJson('/api/v1/cars/delete/' . $car->id);
        $response->assertOk();
    }
}
