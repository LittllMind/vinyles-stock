<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_cannot_access_admin_routes()
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get(route('vinyles.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($client)->get(route('stats'));
        $response->assertStatus(403);
    }

    public function test_employe_can_access_admin_routes()
    {
        $employe = User::factory()->create(['role' => 'employe']);

        $response = $this->actingAs($employe)->get(route('vinyles.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($employe)->get(route('stats'));
        $response->assertStatus(200);
    }
}
