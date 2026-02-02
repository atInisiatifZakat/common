<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Contracts;

use Illuminate\Contracts\Cache\Factory;
use Illuminate\Contracts\Cache\Repository;

interface TaggableCacheAwareInterface
{
    public function getTagName(): string;

    public function getCache(): Repository;

    public function setCache(Factory $factory): self;

    public function disableCache(): void;

    public function makeForeverCache(): void;

    public function changeCacheLifetime(int $cacheLifetime): void;
}
