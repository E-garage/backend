<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class RefuelingCreationTest extends TestCase
{
    use RefreshDatabase;

    protected UserModel $user;
    protected Car $car;

    protected function setUp(): void
    {
        parent::setUp();

        $car = Car::factory()->create();
        $this->car = $car; //@phpstan-ignore-line
        $this->user = $car->owner; //@phpstan-ignore-line
        $this->actingAs($this->user);

        Storage::fake('refueling_receipt');
    }

    public function testRefuelingWithoutReceiptHasBeenCreatedSuccessfully()
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

        $this->assertDatabaseHas('refuelings', $data);
    }

    public function testRefuelingWithReceiptHasBeenCreatedSuccessfully()
    {
        $file = UploadedFile::fake()->image('test.png');

        $data = [
            'car_id' => $this->car->id,
            'date' => '10/10/2020',
            'FuelType' => 'gasoline',
            'amount' => '50',
            'TotalPrice' => '150',
            'receipt' => $file,
        ];

        $response = $this->postJson('/api/v1/refueling/add', $data);
        $response->assertCreated();

        $data['receipt'] = $file->hashName();

        $this->assertDatabaseHas('refuelings', $data);
        Storage::disk('refueling_receipt')->assertExists($file->hashName());
    }

    public function testEmptyRequestIsRejected()
    {
        $response = $this->postJson('/api/v1/refueling/add', []);
        $response->assertUnprocessable();
    }

    public function testRequestWithoutDateInBodyIsRejected()
    {
        $data = [
            'car_id' => $this->car->id,
            'FuelType' => 'gasoline',
            'amount' => '50',
            'TotalPrice' => '150',
        ];

        $response = $this->postJson('/api/v1/refueling/add', $data);
        $response->assertUnprocessable();
    }

    public function testRequestWithInvalidDateInBodyIsRejected()
    {
        $data = [
            'car_id' => $this->car->id,
            'date' => '10/10/200000',
            'FuelType' => 'gasoline',
            'amount' => '50',
            'TotalPrice' => '150',
        ];

        $response = $this->postJson('/api/v1/refueling/add', $data);
        $response->assertUnprocessable();
    }

    public function testRequestWithValidDateInBodyIsCreated()
    {
        $data = [
            'car_id' => $this->car->id,
            'date' => '10/10/21',
            'FuelType' => 'gasoline',
            'amount' => '50',
            'TotalPrice' => '150',
        ];

        $response = $this->postJson('/api/v1/refueling/add', $data);
        $response->assertCreated();
    }

    public function testRequestWithInvalidTotalPriceInBodyIsRejected()
    {
        $data = [
            'car_id' => $this->car->id,
            'date' => '10/10/2020',
            'FuelType' => 'gasoline',
            'amount' => '50',
            'TotalPrice' => 123456,
        ];

        $response = $this->postJson('/api/v1/refueling/add', $data);
        $response->assertUnprocessable();
    }

    public function testRequestWithPdfFileExtensionIsRejected()
    {
        $file = UploadedFile::fake()->create('test', 15, 'pdf');

        $data = [
            'car_id' => $this->car->id,
            'date' => '10/10/2020',
            'FuelType' => 'gasoline',
            'amount' => '50',
            'TotalPrice' => '150',
            'receipt' => $file,
        ];

        $response = $this->postJson('/api/v1/refueling/add', $data);
        $response->assertUnprocessable();
    }

    public function testRequestWithSvgFileExtensionIsRejected()
    {
        $file = UploadedFile::fake()->create('test', 15, 'svg');

        $data = [
            'car_id' => $this->car->id,
            'date' => '10/10/2020',
            'FuelType' => 'gasoline',
            'amount' => '50',
            'TotalPrice' => '150',
            'receipt' => $file,
        ];

        $response = $this->postJson('/api/v1/refueling/add', $data);
        $response->assertUnprocessable();
    }

    public function testRequestWithInvalidFileInBodyIsRejected()
    {
        $data = [
            'car_id' => $this->car->id,
            'date' => '10/10/2020',
            'FuelType' => 'gasoline',
            'amount' => '50',
            'TotalPrice' => '150',
            'receipt' => 123456,
        ];

        $response = $this->postJson('/api/v1/refueling/add', $data);
        $response->assertUnprocessable();

        $data = [
            'car_id' => $this->car->id,
            'date' => '10/10/2020',
            'FuelType' => 'gasoline',
            'amount' => '50',
            'TotalPrice' => '150',
            'receipt' => 'it cannot pass the guards',
        ];

        $response = $this->postJson('/api/v1/refueling/add', $data);
        $response->assertUnprocessable();
    }
}
