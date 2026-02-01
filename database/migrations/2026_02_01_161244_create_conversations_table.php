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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meta_asset_id')->constrained()->cascadeOnDelete();
            $table->string('ig_conversation_id')->nullable();
            $table->boolean('archived')->default(false);
            $table->unsignedInteger('unread_count')->default(0);
            $table->timestamps();
        });
        Schema::table('conversations', function (Blueprint $table) {
            $table->index(['meta_asset_id', 'ig_conversation_id']);
            $table->index('archived');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
