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
        Schema::create('brand_voices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignId('meta_asset_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tone', 100)->nullable();
            $table->string('style', 100)->nullable();
            $table->json('keywords')->nullable();
            $table->json('avoid_words')->nullable();
            $table->json('compliance_rules')->nullable();
            $table->string('language', 10)->nullable();
            $table->timestamps();
        });

        Schema::table('brand_voices', function (Blueprint $table) {
            $table->unique(['workspace_id', 'meta_asset_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_voices');
    }
};
