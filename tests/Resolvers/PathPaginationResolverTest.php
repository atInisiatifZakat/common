<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Tests\Resolvers;

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Inisiatif\Package\Common\Resolvers\PathPaginationResolver;

final class PathPaginationResolverTest extends TestCase
{
    public function testCanCreateDefaultPath(): void
    {
        $request = new Request();

        $this->assertSame('http://:', PathPaginationResolver::resolveFor('default', $request));
    }

    public function testCanCreatePathFromKongProxy(): void
    {
        $request = new Request([], [], [], [], [], [
            'HTTP_X_FORWARDED_SCHEME' => 'http',
            'HTTP_X_FORWARDED_HOST' => 'localhost',
            'HTTP_X_FORWARDED_PREFIX' => null,
        ]);

        $url = PathPaginationResolver::resolveFor(PathPaginationResolver::PROXY, $request);
        $this->assertSame('http://localhost', $url);

        $request = new Request([], [], [], [], [], [
            'HTTP_X_FORWARDED_SCHEME' => 'https',
            'HTTP_X_FORWARDED_HOST' => 'apiproxy.inisiatif.id',
            'HTTP_X_FORWARDED_PREFIX' => '/foo/bar',
        ]);

        $url = PathPaginationResolver::resolveFor(PathPaginationResolver::PROXY, $request);
        $this->assertSame('https://apiproxy.inisiatif.id/foo/bar', $url);

        $request = new Request([], [], [], [], [], [
            'HTTP_X_FORWARDED_SCHEME' => 'https',
            'HTTP_X_FORWARDED_HOST' => 'apiproxy.inisiatif.id',
            'HTTP_X_FORWARDED_PREFIX' => '/foo/bar',
            'QUERY_STRING' => 'foo=bar',
        ]);

        $url = PathPaginationResolver::resolveFor(PathPaginationResolver::PROXY, $request);
        $this->assertSame('https://apiproxy.inisiatif.id/foo/bar', $url);
    }
}
