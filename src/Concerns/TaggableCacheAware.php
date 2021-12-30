<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Concerns;

use Closure;
use RuntimeException;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Contracts\Cache\Repository;

trait TaggableCacheAware
{
    protected int $cacheLifetime = 0;

    private ?Factory $factory = null;

    abstract public function getTagName(): string;

    public function getCache(): Repository
    {
        if ($this->factory === null) {
            /** @var Factory $cache */
            $cache = app('cache');

            $this->factory = $cache;
        }

        $store = $this->factory->store();

        if (! \method_exists($store, 'tags')) {
            throw new RuntimeException('This cache store does not support tagging.');
        }

        return $store;
    }

    public function setCache(Factory $factory): self
    {
        $this->factory = $factory;

        return $this;
    }

    public function disableCache(): void
    {
        $this->changeCacheLifetime(0);
    }

    public function makeForeverCache(): void
    {
        $this->changeCacheLifetime(-1);
    }

    public function changeCacheLifetime(int $cacheLifetime): void
    {
        $this->cacheLifetime = $cacheLifetime;
    }

    /**
     * @return mixed
     */
    protected function rememberCache(string $class, string $method, array $args, Closure $callback)
    {
        $lifetime = $this->getCacheLifetime();

        $hash = \md5(\json_encode($args));

        $cacheKey = $class . '@' . $method . '.' . $hash;

        /** @psalm-suppress UndefinedInterfaceMethod */
        $store = $this->getCache()->tags($this->getTagName());

        return $lifetime === -1 ?
            $store->rememberForever($cacheKey, $callback) :
            $store->remember($cacheKey, $lifetime, $callback);
    }

    protected function flushCache(): bool
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        return $this->getCache()->tags($this->getTagName())->flush();
    }

    private function getCacheLifetime(): int
    {
        return $this->cacheLifetime;
    }
}
