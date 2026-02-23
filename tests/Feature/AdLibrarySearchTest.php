<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdLibrarySearchTest extends TestCase
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

    public function test_search_returns_stub_results_with_valid_params(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/ad-library/search', [
            'query' => 'california',
            'countries' => ['US'],
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'ok',
            'data' => [
                'data',
                'paging' => [
                    'next_cursor',
                    'has_more',
                ],
            ],
        ]);
        $data = $response->json('data.data');
        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('ad_archive_id', $data[0]);
        $this->assertArrayHasKey('page_name', $data[0]);
    }

    public function test_search_requires_countries(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/ad-library/search', [
            'query' => 'test',
            'countries' => [],
        ]);

        $response->assertUnprocessable();
    }

    public function test_search_requires_query_or_page_ids(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/ad-library/search', [
            'query' => '',
            'countries' => ['US'],
        ]);

        $response->assertUnprocessable();
    }

    public function test_search_requires_authentication(): void
    {
        $response = $this->postJson('/api/ad-library/search', [
            'query' => 'test',
            'countries' => ['US'],
        ]);
        $response->assertUnauthorized();
    }
}
