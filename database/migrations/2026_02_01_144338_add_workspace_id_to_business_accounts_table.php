<?php

use App\Models\BusinessAccount;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('business_accounts', function (Blueprint $table) {
            $table->foreignId('workspace_id')->nullable()->after('user_id')->constrained()->cascadeOnDelete();
        });

        $this->backfillWorkspaces();
    }

    private function backfillWorkspaces(): void
    {
        $userIds = BusinessAccount::query()->distinct()->pluck('user_id');
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if (! $user) {
                continue;
            }
            $name = $user->name."'s Workspace";
            $slug = Str::slug($user->name);
            $base = $slug;
            $i = 0;
            while (Workspace::where('slug', $slug)->exists()) {
                $slug = $base.'-'.(++$i);
            }
            $workspace = Workspace::create([
                'name' => $name,
                'slug' => $slug,
                'subscription_tier' => 'free',
                'subscription_status' => 'active',
            ]);
            $workspace->users()->attach($userId);
            BusinessAccount::where('user_id', $userId)->update(['workspace_id' => $workspace->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_accounts', function (Blueprint $table) {
            $table->dropForeign(['workspace_id']);
        });
    }
};
