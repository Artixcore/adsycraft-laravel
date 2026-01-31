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
        Schema::create('business_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('timezone', 50);
            $table->boolean('autopilot_enabled')->default(false);
            $table->string('meta_page_id')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::table('business_accounts', function (Blueprint $table) {
            $table->index('autopilot_enabled');
            $table->index('meta_page_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_accounts');
    }
};
