<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Models;

use Illuminate\Database\Eloquent\Model;
use Inisiatif\Package\Common\Concerns\UuidPrimaryKey;

final class Branch extends Model
{
    use UuidPrimaryKey;

    public function getTable(): string
    {
        return \config('common.tables.branch', parent::getTable());
    }
}
