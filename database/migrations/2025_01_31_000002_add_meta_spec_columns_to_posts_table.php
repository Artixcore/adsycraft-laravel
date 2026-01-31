<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('channel', 50)->nullable()->after('business_account_id');
            $table->text('caption')->nullable()->after('channel');
            $table->string('media_type', 50)->nullable()->after('caption');
            $table->text('media_prompt')->nullable()->after('media_type');
            $table->text('error_message')->nullable()->after('published_at');
        });

        if (Schema::hasColumn('posts', 'content')) {
            DB::table('posts')->orderBy('id')->chunk(100, function ($posts) {
                foreach ($posts as $post) {
                    DB::table('posts')->where('id', $post->id)->update(['caption' => $post->content]);
                }
            });
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('content');
            });
        }

        if (Schema::hasColumn('posts', 'meta_post_id')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->renameColumn('meta_post_id', 'provider_post_id');
            });
        } else {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('provider_post_id')->nullable()->after('error_message');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('posts', 'provider_post_id')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->renameColumn('provider_post_id', 'meta_post_id');
            });
        }
        Schema::table('posts', function (Blueprint $table) {
            $table->text('content')->nullable()->after('media_prompt');
        });
        if (Schema::hasColumn('posts', 'caption')) {
            DB::table('posts')->orderBy('id')->chunk(100, function ($posts) {
                foreach ($posts as $post) {
                    DB::table('posts')->where('id', $post->id)->update(['content' => $post->caption ?? '']);
                }
            });
        }
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['channel', 'caption', 'media_type', 'media_prompt', 'error_message']);
        });
    }
};
