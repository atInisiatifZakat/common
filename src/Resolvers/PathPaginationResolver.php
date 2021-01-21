<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Resolvers;

use Illuminate\Http\Request;

final class PathPaginationResolver
{
    public const PROXY = 'proxy';

    public static function resolveFor(string $name, Request $request): string
    {
        $self = new self();

        switch (strtolower($name)) {
            case self::PROXY:
                return $self->makeForKongProxy($request);
            default:
                return $self->makeDefault($request);
        }
    }

    private function makeForKongProxy(Request $request): string
    {
        /** @var string $schema */
        $schema = $request->header('X-Forwarded-Scheme', 'http');

        /** @var string $host */
        $host = $request->header('X-Forwarded-Host', $request->getHost());

        /** @var string $path */
        $path = $request->header('X-Forwarded-Prefix', '/' . $request->path());

        $query = $request->getQueryString() ? '?' . $request->getQueryString() : '';

        return $schema . '://' . $host . $path . $query;
    }

    private function makeDefault(Request $request): string
    {
        return $request->url();
    }
}
