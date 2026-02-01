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
        Schema::create('product_research', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meta_asset_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('business_account_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->json('price_hints')->nullable();
            $table->json('pain_points')->nullable();
            $table->json('sources')->nullable();
            $table->decimal('confidence', 5, 4)->nullable();
            $table->timestamps();
        });

        Schema::table('product_research', function (Blueprint $table) {
            $table->index(['meta_asset_id', 'business_account_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_research');
    }
};
