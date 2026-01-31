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
        Schema::table('business_accounts', function (Blueprint $table) {
            $table->string('niche')->nullable()->after('name');
            $table->string('website_url', 500)->nullable()->after('niche');
            $table->string('tone', 100)->nullable()->after('website_url');
            $table->string('language', 10)->nullable()->after('tone');
            $table->unsignedTinyInteger('posts_per_day')->default(1)->after('timezone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_accounts', function (Blueprint $table) {
            $table->dropColumn(['niche', 'website_url', 'tone', 'language', 'posts_per_day']);
        });
    }
};
