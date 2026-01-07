<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kullanıcı giriş yapmamışsa login sayfasına yönlendir
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Bu sayfaya erişmek için giriş yapmalısınız.');
        }

        // Admin değilse ana sayfaya yönlendir
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('user.profile')
                ->with('error', 'Bu sayfaya erişim yetkiniz bulunmamaktadır.');
        }

        return $next($request);
    }
}
