<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Convert permission string to method name
        $methodName = 'can' . str_replace('_', '', ucwords($permission, '_'));
        
        if (method_exists($user, $methodName) && $user->$methodName()) {
            return $next($request);
        }

        return redirect()->back()->with('error', 'You do not have permission to access this page.');
    }
}