<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Concerns;

use Inisiatif\Package\Common\Models\Branch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasBranch
{
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class)->withoutGlobalScopes();
    }
}
