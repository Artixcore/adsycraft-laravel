<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OAuthConnection;
use Illuminate\View\View;

class MetaAccountController extends Controller
{
    public function index(): View
    {
        $connections = OAuthConnection::query()
            ->where('provider', 'meta')
            ->with('businessAccount:id,name,user_id', 'businessAccount.user:id,name,email')
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('admin.meta-accounts.index', ['connections' => $connections]);
    }
}
