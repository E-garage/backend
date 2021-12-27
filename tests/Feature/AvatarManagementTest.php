<?php

namespace Tests\Feature;

use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class AvatarManagementTest extends TestCase
{
    use RefreshDatabase;

    protected UserModel $user;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('user_avatars');
        $this->actAsUser();
    }

    public function testAvatarUplodedSuccessfully()
    {
        $file = UploadedFile::fake()->image('test.png');
        $response = $this->postJson('/api/v1/account/avatar/upload', ['image' => $file]);

        $response->assertOk();
        Storage::disk('user_avatars')->assertExists($file->hashName());
    }

    public function testFilenameWasPersistedToDatabase()
    {
        $file = UploadedFile::fake()->image('test.png');
        $this->postJson('/api/v1/account/avatar/upload', ['image' => $file]);
        $filename = $this->user['avatar'];

        $this->assertNotNull($filename);
    }

    public function testPersistedFilenameAndOriginalOneAreTheSame()
    {
        $file = UploadedFile::fake()->image('test.png');
        $this->postJson('/api/v1/account/avatar/upload', ['image' => $file]);
        $filenameFromDatabase = $this->user['avatar'];
        $filename = $file->hashName();

        $this->assertSame($filename, $filenameFromDatabase);
    }

    public function testAvatarWasRetrievedFromStorage()
    {
        $file = UploadedFile::fake()->image('test.png');
        $this->postJson('/api/v1/account/avatar/upload', ['image' => $file]);

        $filename = $this->user['avatar'];
        $response = $this->getJson('/api/v1/account/avatar/');

        $response->assertOk();
        $response->assertDownload($filename);
    }

    public function testAvatarWasDeletedFromTheStorage()
    {
        $file = UploadedFile::fake()->image('test.png');
        $this->postJson('/api/v1/account/avatar/upload', ['image' => $file]);

        $filename = $this->user['avatar'];
        $response = $this->deleteJson('/api/v1/account/avatar/delete');

        $this->assertNotNull($filename);
        $response->assertOk();
        Storage::disk('user_avatars')->assertMissing($filename);
    }

    public function testFilenameWasDeletedFromDatabase()
    {
        $filename = $this->user['avatar'];

        $this->assertNull($filename);
    }

    public function testEmptyRequestWasRejected()
    {
        $response = $this->postJson('/api/v1/account/avatar/upload');

        $response->assertUnprocessable();
    }

    public function testRequestWithStringWasRejected()
    {
        $response = $this->postJson('/api/v1/account/avatar/upload', ['image' => 'text-instead-of-image']);

        $response->assertUnprocessable();
        $response->assertInvalid(['image']);
    }

    public function testRequestWithIntegerWasRejected()
    {
        $response = $this->postJson('/api/v1/account/avatar/upload', ['image' => 12345]);

        $response->assertUnprocessable();
        $response->assertInvalid(['image']);
    }

    public function testRequestWithPdfFileWasRejected()
    {
        $file = UploadedFile::fake()->create('fake', 0, 'pdf');
        $response = $this->postJson('/api/v1/account/avatar/upload', ['image' => $file]);

        $response->assertUnprocessable();
        $response->assertInvalid(['image']);
    }

    public function testRequestWithSvgFileWasRejected()
    {
        $file = UploadedFile::fake()->create('fake', 0, 'svg');
        $response = $this->postJson('/api/v1/account/avatar/upload', ['image' => $file]);

        $response->assertUnprocessable();
        $response->assertInvalid(['image']);
    }

    private function actAsUser()
    {
        $this->user = UserModel::factory()->create(); // @phpstan-ignore-line
        $this->actingAs($this->user); // @phpstan-ignore-line
    }
}
