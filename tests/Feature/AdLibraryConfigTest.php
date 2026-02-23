<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdLibraryConfigTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.meta.ad_library.enabled' => true,
            'services.meta.ad_library.default_country' => 'BD',
        ]);
    }

    public function test_config_returns_allowed_filters_and_disclaimer(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('/api/ad-library/config');

        $response->assertOk();
        $response->assertJson([
            'ok' => true,
            'data' => [
                'enabled' => true,
                'default_country' => 'BD',
            ],
        ]);
        $response->assertJsonStructure([
            'data' => [
                'ad_active_status_options',
                'ad_type_options',
                'media_type_options',
                'publisher_platforms',
                'disclaimer',
            ],
        ]);
    }

    public function test_config_returns_403_when_disabled(): void
    {
        config(['services.meta.ad_library.enabled' => false]);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('/api/ad-library/config');

        $response->assertStatus(403);
        $response->assertJson(['ok' => false, 'message' => 'Ad Library is not enabled.']);
    }

    public function test_config_requires_authentication(): void
    {
        $response = $this->getJson('/api/ad-library/config');
        $response->assertUnauthorized();
    }
}
