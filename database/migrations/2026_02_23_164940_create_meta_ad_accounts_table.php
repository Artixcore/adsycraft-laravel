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
        Schema::create('meta_ad_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_account_id')->constrained()->cascadeOnDelete();
            $table->string('meta_ad_account_id');
            $table->string('name')->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('account_status')->nullable();
            $table->boolean('selected')->default(false);
            $table->timestamps();
            $table->unique(['business_account_id', 'meta_ad_account_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta_ad_accounts');
    }
};
