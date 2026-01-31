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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('meta_asset_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('content_pillar_id')->nullable()->constrained()->nullOnDelete();
            $table->text('content');
            $table->string('media_url')->nullable();
            $table->timestamp('scheduled_at');
            $table->timestamp('published_at')->nullable();
            $table->string('status', 20)->default('draft'); // draft, scheduled, publishing, published, failed
            $table->string('meta_post_id')->nullable();
            $table->timestamps();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->index('scheduled_at');
            $table->index('status');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
