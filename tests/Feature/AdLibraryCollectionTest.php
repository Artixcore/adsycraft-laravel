<?php

namespace Tests\Feature;

use App\Models\AdLibraryCollection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdLibraryCollectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.meta.ad_library.enabled' => true]);
    }

    public function test_user_can_create_collection(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/ad-library/collections', [
            'name' => 'My Ads',
        ]);

        $response->assertCreated();
        $response->assertJson([
            'data' => [
                'name' => 'My Ads',
            ],
        ]);
        $this->assertDatabaseHas('ad_library_collections', [
            'user_id' => $user->id,
            'name' => 'My Ads',
        ]);
    }

    public function test_user_can_list_own_collections(): void
    {
        $user = User::factory()->create();
        AdLibraryCollection::factory()->count(2)->for($user)->create();

        $otherUser = User::factory()->create();
        AdLibraryCollection::factory()->for($otherUser)->create(['name' => 'Other collection']);

        $response = $this->actingAs($user)->getJson('/api/ad-library/collections');

        $response->assertOk();
        $data = $response->json('data');
        $this->assertCount(2, $data);
        $this->assertNotContains('Other collection', array_column($data, 'name'));
    }

    public function test_user_can_add_item_to_collection(): void
    {
        $user = User::factory()->create();
        $collection = AdLibraryCollection::factory()->for($user)->create();

        $response = $this->actingAs($user)->postJson("/api/ad-library/collections/{$collection->id}/items", [
            'ad_archive_id' => '12345',
            'page_name' => 'Test Page',
            'snapshot_url' => 'https://facebook.com/ads/library/?id=12345',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('ad_library_collection_items', [
            'ad_library_collection_id' => $collection->id,
            'ad_archive_id' => '12345',
        ]);
    }

    public function test_user_cannot_add_item_to_other_users_collection(): void
    {
        $owner = User::factory()->create();
        $collection = AdLibraryCollection::factory()->for($owner)->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->postJson("/api/ad-library/collections/{$collection->id}/items", [
            'ad_archive_id' => '12345',
        ]);

        $response->assertNotFound();
    }

    public function test_user_can_remove_item_from_collection(): void
    {
        $user = User::factory()->create();
        $collection = AdLibraryCollection::factory()->for($user)->create();
        $item = $collection->items()->create([
            'ad_archive_id' => '12345',
            'page_name' => 'Test',
        ]);

        $response = $this->actingAs($user)->deleteJson("/api/ad-library/collections/{$collection->id}/items/{$item->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('ad_library_collection_items', ['id' => $item->id]);
    }
}
