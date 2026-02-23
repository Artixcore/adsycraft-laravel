<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ad_library_collection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_library_collection_id')->constrained('ad_library_collections')->cascadeOnDelete();
            $table->string('ad_archive_id');
            $table->string('snapshot_url')->nullable();
            $table->string('page_name')->nullable();
            $table->text('ad_creative_body')->nullable();
            $table->string('page_id')->nullable();
            $table->json('publisher_platforms')->nullable();
            $table->string('ad_delivery_start_time')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('ad_library_collection_id');
            $table->index('ad_archive_id');
            $table->unique(['ad_library_collection_id', 'ad_archive_id'], 'ad_lib_collection_items_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_library_collection_items');
    }
};
