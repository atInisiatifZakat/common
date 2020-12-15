<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Abstracts;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

abstract class ModelBuilder
{
    /**
     * @return mixed
     */
    abstract public static function makeFromRequest(Model $model, Request $request);

    /**
     * @return mixed
     */
    abstract public static function makeFromArray(Model $model, array $attributes);
}
