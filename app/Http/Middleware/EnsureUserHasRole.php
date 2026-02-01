<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $roles  Comma-separated list of allowed roles (e.g. "admin,user")
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated.'], 401)
                : redirect()->guest(route('login'));
        }

        $allowed = array_map(
            fn (string $r) => Role::tryFrom(strtolower($r)),
            $roles
        );
        $allowed = array_filter($allowed);

        if ($allowed === [] || ! in_array($user->role, $allowed, true)) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthorized.'], 403)
                : abort(403);
        }

        return $next($request);
    }
}
