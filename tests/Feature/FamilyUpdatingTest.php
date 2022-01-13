<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Family;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamilyUpdatingTest extends TestCase
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
        $this->postJson('/api/v1/family-sharing/create', ['name' => 'testing2']);

        $this->user = UserModel::factory()->create(['name' => 'Greg']); //@phpstan-ignore-line
        $this->actingAs($this->user); //@phpstan-ignore-line

        $this->postJson('/api/v1/family-sharing/create', ['name' => 'testing testing']);
        $this->postJson('/api/v1/family-sharing/create', ['name' => 'testing testing2']);
    }

    public function testOwnerCanUpdateFamilyDetails()
    {
        $family = $this->user->createdFamilies()->first();

        $this->assertSame('testing testing', $family->name);

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id, ['name' => 'Updated name']);
        $response->assertOk();

        $family->refresh();

        $this->assertSame($family->id, $response['family']['id']);
        $this->assertSame('Updated name', $family->name);
        $this->assertSame('Updated name', $response['family']['name']);
    }

    public function testMemberCanNotUpdateFamilyDetails()
    {
        $family = Family::where('owner_id', '!=', $this->user->id)->first();
        $family->members()->attach($this->user->id);

        $this->assertSame('testing', $family->name);

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id, ['name' => 'Updated name']);
        $response->assertUnauthorized();

        $this->assertSame('testing', $family->name);
    }

    public function testUserCanNotUpdateForeignFamilyDetails()
    {
        $family = Family::doesntHave('members')->where('owner_id', '!=', $this->user->id)->first();

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id, ['name' => 'Updated name']);
        $response->assertUnauthorized();
    }

    public function testOwnerCanUpdateFamilyMembers()
    {
        $family = $this->user->createdFamilies()->first();

        $this->assertEmpty($family->members()->first());

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id . '/members', ['names' => ['Bob']]);
        $response->assertOk();

        $member = $family->members()->first();
        $this->assertSame('Bob', $member->name);

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id . '/members', ['names' => ['Bob']]);
        $response->assertOk();

        $this->assertEmpty($family->members()->first());

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id . '/members', ['emails' => ['test@test.com']]);
        $response->assertOk();

        $member = $family->members()->first();
        $this->assertSame('test@test.com', $member->email);

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id . '/members', ['emails' => ['test@test.com']]);
        $response->assertOk();

        $this->assertEmpty($family->members()->first());
    }

    public function testMemberCanNotUpdateFamilyMembers()
    {
        $family = Family::where('owner_id', '!=', $this->user->id)->first();
        $family->members()->attach($this->user->id);

        $familyMember = UserModel::factory()->create(['name' => 'John', 'email' => 'test@noreply.com']);

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id . '/members', ['emails' => [$familyMember->email]]); //@phpstan-ignore-line
        $response->assertUnauthorized();

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id . '/members', ['names' => [$familyMember->name]]); //@phpstan-ignore-line
        $response->assertUnauthorized();
    }

    public function testUserCanNotUpdateForeignFamilyMembers()
    {
        $family = Family::doesntHave('members')->where('owner_id', '!=', $this->user->id)->first();
        $familyMember = UserModel::factory()->create(['name' => 'John', 'email' => 'test@noreply.com']);

        $this->assertEmpty($family->members()->first());

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id . '/members', ['names' => [$familyMember->name]]); //@phpstan-ignore-line
        $response->assertUnauthorized();

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id . '/members', ['emails' => [$familyMember->email]]); //@phpstan-ignore-line
        $response->assertUnauthorized();

        $this->assertEmpty($family->members()->first());
    }

    public function testOwnerCanUpdateFamilyCars()
    {
        $family = $this->user->createdFamilies()->first();
        $this->assertEmpty($family->cars()->first());

        $car = Car::factory()->create();
        $this->assertEmpty($car->family()->first()); //@phpstan-ignore-line

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id . '/cars', ['cars' => [$car->id]]); //@phpstan-ignore-line
        $response->assertOk();

        $family->refresh();
        $car->refresh();

        $this->assertNotEmpty($family->cars()->first());
        $this->assertNotEmpty($car->family()->first()); //@phpstan-ignore-line

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id . '/' . $car->id . '/detach'); //@phpstan-ignore-line
        $response->assertOk();

        $family->refresh();
        $car->refresh();

        $this->assertEmpty($family->cars()->first());
        $this->assertEmpty($car->family()->first()); //@phpstan-ignore-line
    }

    public function testMemberCanNotUpdateFamilyCars()
    {
        $family = Family::where('owner_id', '!=', $this->user->id)->first();
        $family->members()->attach($this->user->id);
        $this->assertEmpty($family->cars()->first());

        $car = Car::factory()->create();
        $this->assertEmpty($car->family()->first()); //@phpstan-ignore-line

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id . '/cars', ['cars' => [$car->id]]); //@phpstan-ignore-line
        $response->assertUnauthorized();

        $this->assertEmpty($family->cars()->first());
    }

    public function testUserCanNotUpdateForeignFamilyCars()
    {
        $family = Family::doesntHave('members')->where('owner_id', '!=', $this->user->id)->first();
        $this->assertEmpty($family->cars()->first());

        $car = Car::factory()->create();
        $this->assertEmpty($car->family()->first()); //@phpstan-ignore-line

        $response = $this->putJson('/api/v1/family-sharing/update/' . $family->id . '/cars', ['cars' => [$car->id]]); //@phpstan-ignore-line
        $response->assertUnauthorized();

        $this->assertEmpty($family->cars()->first());
    }
}
