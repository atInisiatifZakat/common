<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Concerns;

use Closure;
use LogicException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\ConnectionInterface;

trait EloquentAwareRepository
{
    /**
     * @var string|Model
     */
    protected $model;

    /**
     * @psalm-suppress MixedMethodCall
     */
    public function getModel(): Model
    {
        if (is_string($this->model) && class_exists($this->model)) {
            return new $this->model();
        }

        if ($this->model instanceof Model) {
            return $this->model;
        }

        throw new LogicException('Model must be valid FQN model class or model object.');
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->getModel()->getConnection();
    }

    public function transaction(Closure $callback, int $attempts = 1): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->getConnection()->transaction($callback, $attempts);
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

    protected function setModel(Model $model): void
    {
        $this->model = $model;
    }
}
