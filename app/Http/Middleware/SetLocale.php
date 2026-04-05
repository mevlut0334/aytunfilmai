<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = config('app.supported_locales', ['tr', 'en']);

        // 1. Session'da kayıtlı dil varsa onu kullan
        if (session()->has('locale') && in_array(session('locale'), $supportedLocales)) {
            app()->setLocale(session('locale'));
            return $next($request);
        }

        // 2. Tarayıcının Accept-Language header'ını oku
        foreach ($request->getLanguages() as $browserLocale) {
            $short = strtolower(substr($browserLocale, 0, 2));

            if (in_array($short, $supportedLocales)) {
                app()->setLocale($short);
                session(['locale' => $short]);
                return $next($request);
            }
        }

        // 3. Eşleşme yoksa varsayılan dili kullan
        app()->setLocale(config('app.locale'));

        return $next($request);
    }
}
