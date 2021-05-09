<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Concerns;

use Laminas\Hydrator\AbstractHydrator;
use Inisiatif\Package\Common\Hydrator\ObjectPropertyHydrator;
use Laminas\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

trait HasArrayHydrator
{
    public function toArray(): array
    {
        $hydrator = $this->getHydrator();

        return $hydrator->extract($this);
    }

    protected function getHydrator(): AbstractHydrator
    {
        $hydrator = new ObjectPropertyHydrator();

        $hydrator->setNamingStrategy(
            new UnderscoreNamingStrategy()
        );

        return $hydrator;
    }
}
