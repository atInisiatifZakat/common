<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Concerns;

trait IntranetAware
{
    public function getIntranetId(): ?int
    {
        return $this->getAttribute('intranet_id');
    }

    public function setIntranetId(?int $value): void
    {
        $this->setAttribute('intranet_id', $value);
    }
}
