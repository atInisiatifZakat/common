<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Concerns;

use Webmozart\Assert\Assert;

trait IntegerPrimaryKey
{
    public function getId()
    {
        return $this->getKey();
    }

    public function setId($id): void
    {
        Assert::integer($id);

        $this->setAttribute('id', $id);
    }
}
