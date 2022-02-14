<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Http\Middleware;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Collection;

final class EnsureFrontendRequestsAreStateful
{
    /**
     * @return mixed
     */
    public function handle(Request $request, $next)
    {
        $this->configureSecureCookieSessions();

        return (new Pipeline(app()))->send($request)->through(static::fromFrontend($request) ? [
            function ($request, $next) {
                $request->attributes->set('sanctum', true);

                return $next($request);
            },
            config('sanctum.middleware.encrypt_cookies', \Illuminate\Cookie\Middleware\EncryptCookies::class),
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            config('sanctum.middleware.verify_csrf_token', \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class),
        ] : [])->then(function ($request) use ($next) {
            return $next($request);
        });
    }

    public static function fromFrontend($request): bool
    {
        $domain = $request->headers->get('referer') ?: $request->headers->get('origin');

        if ($domain === null) {
            return false;
        }

        if (Str::contains($domain, 'inisiatif-zakat.vercel.app')) {
            return true;
        }

        $domain = Str::replaceFirst('https://', '', $domain);
        $domain = Str::replaceFirst('http://', '', $domain);
        $domain = Str::endsWith($domain, '/') ? $domain : "{$domain}/";

        $stateful = array_filter(config('sanctum.stateful', []));

        return Str::is(Collection::make($stateful)->map(function ($uri) {
            return trim($uri) . '/*';
        })->all(), $domain);
    }

    private function configureSecureCookieSessions(): void
    {
        config([
            'session.http_only' => true,
            'session.same_site' => 'lax',
        ]);
    }
}
