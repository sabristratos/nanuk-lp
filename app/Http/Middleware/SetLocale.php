<?php

namespace App\Http\Middleware;

use App\Services\LocaleService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected LocaleService $localeService;

    public function __construct(LocaleService $localeService)
    {
        $this->localeService = $localeService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $availableLocales = $this->localeService->getAvailableLocales();
        $defaultLocale = $this->localeService->getDefaultLocale();
        $sessionLocale = $request->session()->get('locale');

        if ($sessionLocale && in_array($sessionLocale, $availableLocales)) {
            app()->setLocale($sessionLocale);
        } else {
            // If no valid session locale, set the default and update the session.
            app()->setLocale($defaultLocale);
            $request->session()->put('locale', $defaultLocale);
        }

        // Share the list of active languages with all views
        View::share('availableLocalesForSwitcher', $this->localeService->getAvailableLocalesForSelect());

        return $next($request);
    }
}
