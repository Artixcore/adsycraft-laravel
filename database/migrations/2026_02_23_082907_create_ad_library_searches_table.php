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
        Schema::create('ad_library_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('business_account_id')->nullable()->constrained('business_accounts')->nullOnDelete();
            $table->string('query');
            $table->json('countries');
            $table->string('ad_active_status')->default('ACTIVE');
            $table->string('media_type')->nullable();
            $table->string('platform')->nullable();
            $table->date('started_after')->nullable();
            $table->date('started_before')->nullable();
            $table->json('search_page_ids')->nullable();
            $table->string('ad_type')->nullable();
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('business_account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_library_searches');
    }
};
