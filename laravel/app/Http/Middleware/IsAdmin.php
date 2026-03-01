<?php

namespace App\Http\Middleware;

use App\Helpers\RolesHelper;
use Closure;
use Illuminate\Http\Request;
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) return redirect()->route('login');
        $user_has_the_role = RolesHelper::user_has_the_role(auth()->id(), 'admin');
        if ($user_has_the_role) return $next($request);

        return redirect()->route('login')->with('error', __('messages.user_has_no_access'));
    }
}