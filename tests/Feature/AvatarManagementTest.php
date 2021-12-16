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

        Storage::fake('user_avatars'); // @phpstan-ignore-line
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

    public function testValidationExceptionHasOccured()
    {
        //empty request
        $response = $this->postJson('/api/v1/account/avatar/upload');
        $response->assertUnprocessable();

        //request with string
        $response = $this->postJson('/api/v1/account/avatar/upload', ['image' => 'text-instead-of-image']);
        $response->assertUnprocessable();

        //request with integer
        $response = $this->postJson('/api/v1/account/avatar/upload', ['image' => 12345]);
        $response->assertUnprocessable();

        //request with pdf file
        $file = UploadedFile::fake()->create('fake', 0, 'pdf');
        $response = $this->postJson('/api/v1/account/avatar/upload', ['image' => $file]);
        $response->assertUnprocessable();

        //request with svg file
        $file = UploadedFile::fake()->create('fake', 0, 'svg');
        $response = $this->postJson('/api/v1/account/avatar/upload', ['image' => $file]);
        $response->assertUnprocessable();
    }

    private function actAsUser()
    {
        $this->user = UserModel::factory()->create(); // @phpstan-ignore-line
        $this->actingAs($this->user); // @phpstan-ignore-line
    }
}
