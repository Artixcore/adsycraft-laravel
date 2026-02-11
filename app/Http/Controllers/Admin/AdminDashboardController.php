<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessAccount;
use App\Models\OAuthConnection;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        $connectedMetaAccounts = OAuthConnection::query()->where('provider', 'meta')->count();
        $activeAutomations = BusinessAccount::query()->where('autopilot_enabled', true)->count();
        $scheduledJobs = Post::query()->where('status', 'scheduled')->count();
        $failedJobsCount = 0;
        if (DB::getSchemaBuilder()->hasTable('failed_jobs')) {
            $failedJobsCount = DB::table('failed_jobs')->count();
        }
        $totalUsers = User::query()->count();
        $totalBusinesses = BusinessAccount::query()->count();

        return view('admin.dashboard', [
            'connectedMetaAccounts' => $connectedMetaAccounts,
            'activeAutomations' => $activeAutomations,
            'scheduledJobs' => $scheduledJobs,
            'failedJobsCount' => $failedJobsCount,
            'totalUsers' => $totalUsers,
            'totalBusinesses' => $totalBusinesses,
        ]);
    }
}
