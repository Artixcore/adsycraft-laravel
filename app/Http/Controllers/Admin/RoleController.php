<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = array_map(fn (Role $role) => $role->value, Role::cases());

        return view('admin.roles.index', ['roles' => $roles]);
    }
}
