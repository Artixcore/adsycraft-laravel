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
        Schema::create('growth_blueprints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_account_id')->constrained()->cascadeOnDelete();
            $table->string('status', 20)->default('draft');
            $table->json('payload');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });

        Schema::table('growth_blueprints', function (Blueprint $table) {
            $table->index(['business_account_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growth_blueprints');
    }
};
