<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if (Auth::user()->role === 'pemeriksa') {
                    return redirect('/pemeriksa/dashboard');
                } elseif (in_array(Auth::user()->role, ['verifikator1', 'verifikator2', 'verifikator3', 'verifikator4'])) {
                    return redirect('/' . Auth::user()->role . '/dashboard');
                }
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
