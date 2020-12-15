<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Abstracts;

use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use SerializesModels;

    /**
     * @return mixed
     */
    abstract public function getModel();

    abstract public function name(): string;
}
