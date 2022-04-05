<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\DataTransfers\Caster;

use DateTimeInterface;
use Illuminate\Support\Carbon;
use Spatie\DataTransferObject\Caster;

final class DateTimeCaster implements Caster
{
    public function cast(mixed $value): DateTimeInterface
    {
        return Carbon::parse($value);
    }
}
