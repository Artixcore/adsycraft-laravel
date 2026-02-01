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
        Schema::create('page_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meta_asset_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('business_account_id')->constrained()->cascadeOnDelete();
            $table->string('period', 20)->default('day');
            $table->date('period_date');
            $table->json('metrics');
            $table->timestamps();
        });

        Schema::table('page_insights', function (Blueprint $table) {
            $table->unique(['meta_asset_id', 'period', 'period_date']);
            $table->index(['business_account_id', 'period_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_insights');
    }
};
