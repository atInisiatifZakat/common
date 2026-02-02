<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\ConnectionInterface;

interface EloquentAwareRepositoryInterface
{
    public function getModel(): Model;

    public function newModelQuery(): Builder;

    public function getConnection(): ConnectionInterface;

    /**
     * @psalm-return mixed
     */
    public function transaction(Closure $callback, int $attempts = 1);

    public function beginTransaction(): void;

    public function commit(): void;

    public function rollBack(): void;
}
