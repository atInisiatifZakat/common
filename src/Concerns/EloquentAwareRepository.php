<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Concerns;

use Closure;
use Exception;
use LogicException;
use ReflectionClass;
use RuntimeException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\ConnectionInterface;
use Inisiatif\Package\Common\Contracts\ResourceInterface;

trait EloquentAwareRepository
{
    /**
     * @var string
     *
     * @psalm-var class-string
     */
    protected $model;

    public function getModel(): Model
    {
        try {
            $reflection = new ReflectionClass($this->model);

            $model = $reflection->newInstanceWithoutConstructor();

            if (($model instanceof Model) && ($model instanceof ResourceInterface)) {
                return $model;
            }

            throw new LogicException(sprintf('Model must be instanceof `%s` and `%s`.', Model::class, ResourceInterface::class));
        } catch (Exception $exception) {
            throw new RuntimeException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }
    }

    public function newModelQuery(): Builder
    {
        return $this->getModel()->newQuery();
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->getModel()->getConnection();
    }

    /**
     * @psalm-return mixed
     */
    public function transaction(Closure $callback, int $attempts = 1)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->getConnection()->transaction($callback, $attempts);
    }

    public function beginTransaction(): void
    {
        $this->getConnection()->beginTransaction();
    }

    public function commit(): void
    {
        $this->getConnection()->commit();
    }

    public function rollBack(): void
    {
        $this->getConnection()->rollBack();
    }
}
