<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessAccount;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AutomationController extends Controller
{
    public function index(Request $request): View
    {
        $query = BusinessAccount::query()->with('user:id,name,email')->orderByDesc('updated_at');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }
        if ($request->filled('status')) {
            if ($request->input('status') === 'autopilot') {
                $query->where('autopilot_enabled', true);
            }
            if ($request->input('status') === 'paused') {
                $query->where('autopilot_enabled', false);
            }
        }

        $automations = $query->paginate(15)->withQueryString();

        return view('admin.automations.index', ['automations' => $automations]);
    }

    public function create(): View
    {
        return view('admin.automations.create');
    }

    public function show(BusinessAccount $business_account): View
    {
        $business_account->load('user:id,name,email');

        return view('admin.automations.show', ['automation' => $business_account]);
    }
}
