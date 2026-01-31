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
        Schema::table('meta_assets', function (Blueprint $table) {
            $table->string('business_portfolio_id')->nullable()->after('business_account_id');
            $table->string('page_id')->nullable()->after('business_portfolio_id');
            $table->string('page_name')->nullable()->after('page_id');
            $table->text('page_access_token')->nullable()->after('page_name');
            $table->string('ig_business_id')->nullable()->after('page_access_token');
            $table->string('ig_username')->nullable()->after('ig_business_id');
            $table->boolean('selected')->default(false)->after('ig_username');
        });

        Schema::table('meta_assets', function (Blueprint $table) {
            $table->index('page_id');
            $table->index('selected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meta_assets', function (Blueprint $table) {
            $table->dropIndex(['page_id']);
            $table->dropIndex(['selected']);
        });
        Schema::table('meta_assets', function (Blueprint $table) {
            $table->dropColumn([
                'business_portfolio_id', 'page_id', 'page_name', 'page_access_token',
                'ig_business_id', 'ig_username', 'selected',
            ]);
        });
    }
};
