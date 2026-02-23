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
        Schema::create('market_intelligence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_account_id')->constrained()->cascadeOnDelete();
            $table->json('research_output')->nullable(); // Research Agent output
            $table->json('trend_output')->nullable(); // Trend Agent output
            $table->json('competitor_ad_data')->nullable(); // Cached Ad Library data
            $table->timestamp('refreshed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('market_intelligence', function (Blueprint $table) {
            $table->unique('business_account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_intelligence');
    }
};
