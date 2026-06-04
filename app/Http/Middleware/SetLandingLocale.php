<?php

namespace App\Http\Middleware;

use App\Support\LanguageRegistry;
use App\Support\LocaleUrl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLandingLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('he-thong*') || $request->is('loginAdmin') || $request->is('logout')) {
            return $next($request);
        }

        if ($request->isMethod('GET') && $request->has('lang')) {
            $lang = $request->query('lang');
            if (is_string($lang) && $lang !== '') {
                $target = LocaleUrl::home(strtolower(trim($lang)));
                $qs = $request->query();
                unset($qs['lang']);
                $query = $qs !== [] ? '?' . http_build_query($qs) : '';

                return redirect($target . $query, 302);
            }
        }

        $urlLocale = LocaleUrl::localeFromRequest($request);
        $active = LanguageRegistry::list();
        if (!isset($active[$urlLocale])) {
            $urlLocale = LocaleUrl::defaultCode();
        }

        $contentLocale = LocaleUrl::contentLocale($urlLocale);

        app()->setLocale($contentLocale);
        $request->attributes->set('url_locale', $urlLocale);
        view()->share('urlLocale', $urlLocale);

        return $next($request);
    }
}
