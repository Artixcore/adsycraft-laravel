<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_set_id')->constrained()->cascadeOnDelete();
            $table->string('meta_ad_id')->nullable();
            $table->string('name');
            $table->string('status', 30)->default('ACTIVE');
            $table->json('creative')->nullable();
            $table->timestamps();
        });
        Schema::table('ads', function (Blueprint $table) {
            $table->index(['ad_set_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
