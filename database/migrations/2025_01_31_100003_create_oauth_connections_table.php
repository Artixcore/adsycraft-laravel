<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oauth_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_account_id')->constrained()->cascadeOnDelete();
            $table->enum('provider', ['meta']);
            $table->text('access_token')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('scopes')->nullable();
            $table->timestamp('connected_at')->nullable();
            $table->timestamps();
            $table->unique(['business_account_id', 'provider']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oauth_connections');
    }
};
