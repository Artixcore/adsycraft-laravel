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
        Schema::create('competitor_urls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_account_id')->constrained()->cascadeOnDelete();
            $table->string('url', 500);
            $table->string('page_name', 255)->nullable();
            $table->string('meta_page_id', 100)->nullable(); // Resolved from Meta Graph API
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('competitor_urls', function (Blueprint $table) {
            $table->index('business_account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitor_urls');
    }
};
