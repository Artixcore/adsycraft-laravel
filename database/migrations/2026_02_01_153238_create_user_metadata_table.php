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
        Schema::create('user_metadata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workspace_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference_type', 30)->default('custom');
            $table->string('key', 100);
            $table->text('value');
            $table->json('tags')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('user_metadata', function (Blueprint $table) {
            $table->index(['user_id', 'reference_type']);
            $table->index(['workspace_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_metadata');
    }
};
