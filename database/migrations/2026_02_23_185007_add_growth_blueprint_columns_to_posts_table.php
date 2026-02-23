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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('post_type', 50)->nullable()->after('content_pillar_id');
            $table->string('hook', 500)->nullable()->after('caption');
            $table->string('cta', 255)->nullable()->after('hook');
            $table->json('hashtags')->nullable()->after('cta');
            $table->text('visual_prompt')->nullable()->after('media_prompt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['post_type', 'hook', 'cta', 'hashtags', 'visual_prompt']);
        });
    }
};
