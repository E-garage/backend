<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Family;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamilyDeletionTest extends TestCase
{
    use RefreshDatabase;

    protected UserModel $user;
    protected UserModel $secondUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->secondUser = UserModel::factory()->create(['name' => 'Bob', 'email' => 'test@test.com']); //@phpstan-ignore-line
        $this->actingAs($this->secondUser); //@phpstan-ignore-line

        $this->postJson('/api/v1/family-sharing/create', ['name' => 'testing']);

        $this->user = UserModel::factory()->create(['name' => 'Greg']); //@phpstan-ignore-line
        $this->actingAs($this->user); //@phpstan-ignore-line

        $this->postJson('/api/v1/family-sharing/create', ['name' => 'testing testing']);
    }

    public function testFamilyIsDeletedSuccessfully()
    {
        $family = $this->user->createdFamilies()->where('name', 'testing testing')->first();
        Car::factory()->create(['family_id' => $family->id]);
        $car = Car::where('family_id', $family->id)->first();
        $family->members()->attach($this->secondUser->id);

        $this->assertDatabaseHas('families', $family->toArray());
        $this->assertDatabaseHas('cars', $car->toArray());

        $this->assertNotEmpty($family->members()->first());
        $this->assertNotEmpty($this->secondUser->families()->first());

        $response = $this->deleteJson('/api/v1/family-sharing/delete/' . $family->id);
        $response->assertOk();

        $car->refresh();

        $this->assertDatabaseMissing('families', $family->toArray());

        $this->assertEmpty($car->family_id); //@phpstan-ignore-line
        $this->assertEmpty($this->user->createdFamilies()->get());
        $this->assertEmpty($this->secondUser->families()->first());
    }

    public function testOwnerCanDeleteFamily()
    {
        $family = $this->user->createdFamilies()->first();
        $this->assertDatabaseHas('families', $family->toArray());

        $response = $this->deleteJson('/api/v1/family-sharing/delete/' . $family->id);
        $response->assertOk();

        $this->assertDatabaseMissing('families', $family->toArray());
    }

    public function testMemberCanNotDeleteFamily()
    {
        $family = Family::where('owner_id', '!=', $this->user->id)->first();
        $family->members()->attach($this->user->id);

        $this->assertDatabaseHas('families', $family->toArray());

        $response = $this->deleteJson('/api/v1/family-sharing/delete/' . $family->id);
        $response->assertUnauthorized();

        $this->assertDatabaseHas('families', $family->toArray());
    }

    public function testUserCanNotDeleteForeignFamily()
    {
        $family = Family::doesntHave('members')->where('owner_id', '!=', $this->user->id)->first();
        $this->assertDatabaseHas('families', $family->toArray());

        $response = $this->deleteJson('/api/v1/family-sharing/delete/' . $family->id);
        $response->assertUnauthorized();

        $this->assertDatabaseHas('families', $family->toArray());
    }
}
