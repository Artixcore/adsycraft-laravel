<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdLibraryCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'services.meta.ad_library.enabled' => true,
            'services.meta.stub' => true,
        ]);
    }

    public function test_identical_searches_return_consistent_results(): void
    {
        $user = User::factory()->create();
        $params = ['query' => 'california', 'countries' => ['US']];

        $response1 = $this->actingAs($user)->postJson('/api/ad-library/search', $params);
        $response2 = $this->actingAs($user)->postJson('/api/ad-library/search', $params);

        $response1->assertOk();
        $response2->assertOk();
        $this->assertEquals($response1->json('data'), $response2->json('data'));
    }
}
