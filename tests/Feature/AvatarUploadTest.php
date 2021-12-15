<?php

namespace Tests\Feature;

use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class AvatarUploadTest extends TestCase
{
    use RefreshDatabase;

    protected string $uri = '/api/v1/account/upload-avatar';
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
        $response = $this->postJson($this->uri, ['image' => $file]);

        $response->assertOk();
        Storage::disk('user_avatars')->assertExists($file->hashName());
    }

    public function testFilenameWasPersistedToDatabase()
    {
        $file = UploadedFile::fake()->image('test.png');
        $response = $this->postJson($this->uri, ['image' => $file]);
        $filename = $this->user['avatar'];

        $this->assertNotNull($filename);
    }

    public function testPersistedFilenameAndOriginalOneAreTheSame()
    {
        $file = UploadedFile::fake()->image('test.png');
        $response = $this->postJson($this->uri, ['image' => $file]);
        $filenameFromDatabase = $this->user['avatar'];
        $filename = $file->hashName();

        $this->assertSame($filename, $filenameFromDatabase);
    }

    public function testValidationExceptionHasOccured()
    {
        //empty request
        $response = $this->postJson($this->uri);
        $response->assertUnprocessable();

        //request with string
        $response = $this->postJson($this->uri, ['image' => 'text-instead-of-image']);
        $response->assertUnprocessable();

        //request with integer
        $response = $this->postJson($this->uri, ['image' => 12345]);
        $response->assertUnprocessable();

        //request with pdf file
        $file = UploadedFile::fake()->create('fake', 0, 'pdf');
        $response = $this->postJson($this->uri, ['image' => $file]);
        $response->assertUnprocessable();

        //request with svg file
        $file = UploadedFile::fake()->create('fake', 0, 'svg');
        $response = $this->postJson($this->uri, ['image' => $file]);
        $response->assertUnprocessable();
    }

    private function actAsUser()
    {
        $this->user = UserModel::factory()->create(); // @phpstan-ignore-line
        $this->actingAs($this->user); // @phpstan-ignore-line
    }
}
