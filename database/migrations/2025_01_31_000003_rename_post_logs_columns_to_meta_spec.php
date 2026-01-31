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
        Schema::table('post_logs', function (Blueprint $table) {
            $table->renameColumn('action', 'level');
            $table->renameColumn('meta_response', 'meta');
        });

        Schema::table('post_logs', function (Blueprint $table) {
            $table->index(['post_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_logs', function (Blueprint $table) {
            $table->dropIndex(['post_id', 'created_at']);
        });

        Schema::table('post_logs', function (Blueprint $table) {
            $table->renameColumn('level', 'action');
            $table->renameColumn('meta', 'meta_response');
        });
    }
};
