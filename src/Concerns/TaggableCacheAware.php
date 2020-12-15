<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Concerns;

use Closure;
use Illuminate\Cache\TagSet;
use Illuminate\Cache\NullStore;
use Illuminate\Cache\TaggedCache;

trait TaggableCacheAware
{
    /**
     * @var int
     */
    protected $cacheLifetime = 0;

    /**
     * @var TaggedCache|null
     */
    private $cache = null;

    abstract public function getTagName(): string;

    public function getCache(): TaggedCache
    {
        $fallbackStore = new NullStore();

        if ($this->cache === null) {
            return new TaggedCache($fallbackStore, new TagSet($fallbackStore));
        }

        return $this->cache;
    }

    public function setCache(TaggedCache $cache): self
    {
        $this->cache = $cache;

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

        $store = $this->getCache()->tags($this->getTagName());

        return $lifetime === -1 ?
            $store->rememberForever($cacheKey, $callback) :
            $store->remember($cacheKey, $lifetime, $callback);
    }

    protected function flushCache(): bool
    {
        return $this->getCache()->tags($this->getTagName())->flush();
    }

    private function getCacheLifetime(): int
    {
        return $this->cacheLifetime;
    }
}
