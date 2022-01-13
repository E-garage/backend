<?php

namespace Tests\Feature;

use App\Models\Family;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FamilyRetrievingTest extends TestCase
{
    use RefreshDatabase;

    protected UserModel $user;
    protected UserModel $secondUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->secondUser = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->secondUser); //@phpstan-ignore-line

        $this->postJson('/api/v1/family-sharing/create', ['name' => 'testing']);
        $this->postJson('/api/v1/family-sharing/create', ['name' => 'testing2']);

        $this->user = UserModel::factory()->create(); //@phpstan-ignore-line
        $this->actingAs($this->user); //@phpstan-ignore-line

        $this->postJson('/api/v1/family-sharing/create', ['name' => 'testing testing']);
        $this->postJson('/api/v1/family-sharing/create', ['name' => 'testing testing2']);
    }

    public function testUserCanRetrieveOnlyHisFamiliesSuccessfully()
    {
        $response = $this->getJson('/api/v1/family-sharing/');
        $response->assertOk();

        $families = $response['families'];

        $this->assertEquals(2, sizeof($families));
        $this->assertEquals('testing testing', $families[0]['name']);
        $this->assertEquals('testing testing2', $families[1]['name']);
    }

    public function testOwnerCanRetrieveFamilyDetails()
    {
        $families = Family::all(['id']);

        $response = $this->getJson('/api/v1/family-sharing/' . $families[0]->id);
        $response->assertUnauthorized();

        $response = $this->getJson('/api/v1/family-sharing/' . $families[1]->id);
        $response->assertUnauthorized();

        $response = $this->getJson('/api/v1/family-sharing/' . $families[2]->id);
        $response->assertOk();

        $this->assertEquals('testing testing', $response['family']['name']);

        $response = $this->getJson('/api/v1/family-sharing/' . $families[3]->id);
        $response->assertOk();

        $this->assertEquals('testing testing2', $response['family']['name']);
    }

    public function testMemberCanRetrieveFamilyDetails()
    {
        $family = Family::where('owner_id', '!=', $this->user->id)->first();
        $this->assertNotSame($this->user->id, $family->owner_id);

        $family->members()->attach($this->user->id);

        $this->assertSame($family->id, $this->user->families()->first()->id);
        $this->assertSame($this->user->id, $family->members()->first()->id);

        $response = $this->getJson('/api/v1/family-sharing/' . $family->id);
        $response->assertOk();
    }

    public function testUserCanNotRetrieveForeignFamilyDetails()
    {
        $family = Family::doesntHave('members')->where('owner_id', '!=', $this->user->id)->first();

        $response = $this->getJson('/api/v1/family-sharing/' . $family->id);
        $response->assertUnauthorized();
    }
}
