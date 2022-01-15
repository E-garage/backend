<?php

namespace Tests\Feature;

use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamilyCreationTest extends TestCase
{
    use RefreshDatabase;

    protected UserModel $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->user); //@phpstan-ignore-line
    }

    public function testUserCanCreateFamilySuccessfully()
    {
        $data = [
            'name' => "Family's name",
            'description' => 'lorem ipsum',
        ];

        $families = $this->user->createdFamilies()->get();
        $this->assertEmpty($families);

        $this->assertDatabaseMissing('families', $data);

        $response = $this->postJson('/api/v1/family-sharing/create', $data);
        $response->assertStatus(201);

        $this->assertDatabaseHas('families', $data);

        $families = $this->user->createdFamilies()->get();
        $this->assertNotEmpty($families);
    }

    public function testUserCanNotCreateFamilyWithSameName()
    {
        $data = [
            'name' => "Family's name",
            'description' => 'lorem ipsum',
        ];

        $families = $this->user->createdFamilies()->get();
        $this->assertEmpty($families);

        $this->assertDatabaseMissing('families', $data);

        $response = $this->postJson('/api/v1/family-sharing/create', $data);
        $response->assertStatus(201);

        $this->assertDatabaseHas('families', $data);

        $families = $this->user->createdFamilies()->get();
        $this->assertNotEmpty($families);

        $response = $this->postJson('/api/v1/family-sharing/create', $data);
        $response->assertStatus(500);
    }

    public function testEmptyRequestWasRejected()
    {
        $response = $this->postJson('/api/v1/family-sharing/create', []);
        $response->assertUnprocessable();
    }

    public function testRequestWithoutNamePropertyWasRejected()
    {
        $data = [
            'description' => 'lorem ipsum',
        ];

        $response = $this->postJson('/api/v1/family-sharing/create', $data);
        $response->assertUnprocessable();
    }
}
