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
        Schema::create('ad_library_results_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_library_search_id')->nullable()->constrained('ad_library_searches')->nullOnDelete();
            $table->string('cache_key')->unique();
            $table->longText('payload');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index('cache_key');
            $table->index('expires_at');
            $table->index('ad_library_search_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_library_results_cache');
    }
};
