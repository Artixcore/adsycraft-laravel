<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()->orderBy('name')->paginate(15);

        return view('admin.users.index', ['users' => $users]);
    }
}
