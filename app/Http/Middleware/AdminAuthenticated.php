<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        \Log::info('Admin middleware check:', [
            'path' => $request->path(),
            'session' => Session::all(),
            'admin_authenticated' => Session::get('admin_authenticated')
        ]);

        if (!Session::get('admin_authenticated')) {
            \Log::info('User not authenticated, redirecting to login');
            return redirect()->route('login');
        }

        \Log::info('User authenticated, proceeding to route');
        return $next($request);
    }
} 