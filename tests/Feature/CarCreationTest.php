<?php

namespace Tests\Feature;

use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Storage;
use Str;
use Tests\TestCase;

class CarCreationTest extends TestCase
{
    use RefreshDatabase;

    protected UserModel $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserModel::factory()->create();
        $this->actingAs($this->user);

        Storage::fake('cars_thumbnails');
    }

    public function testCarWithoutThumbnailHasBeenCreatedSuccessfully()
    {
        $data = [
            'brand' => 'BMW X1',
            'description' => 'Testing description',
        ];

        $response = $this->postJson('/api/v1/cars/add', $data);
        $response->assertCreated();

        $this->assertDatabaseHas('cars', $data);
    }

    public function testCarWithThumbnailHasBeenCreatedSuccessfully()
    {
        $file = UploadedFile::fake()->image('test.png');

        $data = [
            'brand' => 'BMW X1',
            'description' => Str::random(),
            'thumbnail' => $file,
        ];

        $response = $this->postJson('/api/v1/cars/add', $data);
        $response->assertCreated();

        $data['thumbnail'] = $file->hashName();

        $this->assertDatabaseHas('cars', $data);
        Storage::disk('cars_thumbnails')->assertExists($file->hashName());
    }

    public function testEmptyRequestIsRejected()
    {
        $response = $this->postJson('/api/v1/cars/add', []);
        $response->assertUnprocessable();
    }

    public function testRequestWithoutBrandInBodyIsRejected()
    {
        $data = [
            'description' => 'Testing description',
        ];

        $response = $this->postJson('/api/v1/cars/add', $data);
        $response->assertUnprocessable();
    }

    public function testRequestWithInvalidBrandInBodyIsRejected()
    {
        $data = [
            'brand' => 123456,
        ];

        $response = $this->postJson('/api/v1/cars/add', $data);
        $response->assertUnprocessable();
    }

    public function testRequestWithTooShortBrandInBodyIsRejected()
    {
        $data = [
            'brand' => '12',
        ];

        $response = $this->postJson('/api/v1/cars/add', $data);
        $response->assertUnprocessable();
    }

    public function testRequestWithTooLongBrandInBodyIsRejected()
    {
        $data = [
            'brand' => Str::random(31),
        ];

        $response = $this->postJson('/api/v1/cars/add', $data);
        $response->assertUnprocessable();
    }

    public function testRequestWithInvalidDescriptionInBodyIsRejected()
    {
        $data = [
            'description' => 123456,
        ];

        $response = $this->postJson('/api/v1/cars/add', $data);
        $response->assertUnprocessable();
    }

    public function testRequestWithTooLongDescriptionInBodyIsRejected()
    {
        $data = [
            'description' => Str::random(51),
        ];

        $response = $this->postJson('/api/v1/cars/add', $data);
        $response->assertUnprocessable();
    }

    public function testRequestWithPdfFileExtensionIsRejected()
    {
        $file = UploadedFile::fake()->create('test', 15, 'pdf');

        $data = [
            'brand' => 'BMW X1',
            'description' => Str::random(),
            'thumbnail' => $file,
        ];

        $response = $this->postJson('/api/v1/cars/add', $data);
        $response->assertUnprocessable();
    }

    public function testRequestWithSvgFileExtensionIsRejected()
    {
        $file = UploadedFile::fake()->create('test', 15, 'svg');

        $data = [
            'brand' => 'BMW X1',
            'description' => Str::random(),
            'thumbnail' => $file,
        ];

        $response = $this->postJson('/api/v1/cars/add', $data);
        $response->assertUnprocessable();
    }

    public function testRequestWithInvalidFileInBodyIsRejected()
    {
        $data = [
            'brand' => 'BMW X1',
            'description' => Str::random(),
            'thumbnail' => 123456,
        ];

        $response = $this->postJson('/api/v1/cars/add', $data);
        $response->assertUnprocessable();

        $data = [
            'brand' => 'BMW X1',
            'description' => Str::random(),
            'thumbnail' => 'it cannot pass the guards',
        ];

        $response = $this->postJson('/api/v1/cars/add', $data);
        $response->assertUnprocessable();
    }
}
