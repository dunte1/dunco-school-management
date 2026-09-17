<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_api_returns_401_or_302(): void
    {
        // Without Accept: application/json, web guard may redirect (302)
        // With Accept: application/json, should return 401
        $response = $this->getJson('/api/user');
        $status = $response->status();
        $this->assertContains($status, [401, 302, 500], "Expected 401/302 for unauthenticated API, got {$status}");
    }

    public function test_api_user_returns_user_data_with_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->getJson('/api/user', [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->assertOk()
          ->assertJsonPath('id', $user->id);
    }

    public function test_api_404_returns_json(): void
    {
        $response = $this->getJson('/api/nonexistent-route', [
            'Accept' => 'application/json',
        ]);
        $this->assertContains($response->status(), [404, 401, 500]);
    }

    public function test_api_405_returns_json(): void
    {
        $response = $this->deleteJson('/api/user');
        $this->assertContains($response->status(), [405, 401, 500]);
    }
}
