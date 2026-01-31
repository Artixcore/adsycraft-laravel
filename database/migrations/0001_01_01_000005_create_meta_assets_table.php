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
        Schema::create('meta_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_account_id')->constrained()->cascadeOnDelete();
            $table->string('type', 30); // page, instagram_account
            $table->string('meta_id'); // platform ID
            $table->string('name');
            $table->text('access_token'); // encrypted at model level
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamps();
        });

        Schema::table('meta_assets', function (Blueprint $table) {
            $table->unique(['business_account_id', 'type', 'meta_id']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_assets');
    }
};
