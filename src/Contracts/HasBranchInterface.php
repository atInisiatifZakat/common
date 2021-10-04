<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Contracts;

interface HasBranchInterface
{
    /**
     * @return mixed
     */
    public function branch();
}
