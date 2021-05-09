<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Abstracts;

use Illuminate\Support\Str;
use Webmozart\Assert\Assert;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Inisiatif\Package\Common\Concerns\TaggableCacheAware;
use Inisiatif\Package\Common\Concerns\EloquentAwareRepository;
use Inisiatif\Package\Contract\Common\Model\ResourceInterface;
use Inisiatif\Package\Contract\Common\Concern\TaggableCacheAwareInterface;
use Inisiatif\Package\Contract\Common\Repository\ModelRepositoryInterface;
use Inisiatif\Package\Contract\Common\Repository\EloquentAwareRepositoryInterface;

abstract class AbstractRepository implements ModelRepositoryInterface, TaggableCacheAwareInterface, EloquentAwareRepositoryInterface
{
    use TaggableCacheAware;
    use EloquentAwareRepository;

    /**
     * @noinspection PhpUndefinedMethodInspection
     */
    public function save(ResourceInterface $resource): bool
    {
        Assert::isInstanceOf($resource, Model::class);

        $resource->save();

        $this->flushCache();

        return true;
    }

    public function delete($id): void
    {
        $model = $this->findUsingId($id);

        if ($model) {
            $model->delete();
        }

        $this->flushCache();
    }

    public function findUsingId($id)
    {
        return $this->rememberCache(static::class, __FUNCTION__, func_get_args(), function () use ($id): ?ResourceInterface {
            return $id instanceof UuidInterface ? $this->findOneUsingColumn('id', $id->toString()) : $this->findOneUsingColumn('id', $id);
        });
    }

    /**
     * @psalm-suppress MissingReturnType
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function findOneUsingColumn(string $column, $value)
    {
        $model = $this->rememberCache(static::class, __FUNCTION__, func_get_args(), function () use ($column, $value): ?ResourceInterface {
            /** @noinspection PhpIncompatibleReturnTypeInspection */
            return $this->getModel()->newQuery()->where($column, $value)->first();
        });

        Assert::nullOrIsInstanceOf($model, ResourceInterface::class);

        return $model;
    }

    /**
     * @psalm-suppress ImplicitToStringCast
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function findOneUsingColumns(array $columns)
    {
        $model = $this->rememberCache(static::class, __FUNCTION__, func_get_args(), function () use ($columns): ?ResourceInterface {
            /** @noinspection PhpIncompatibleReturnTypeInspection */
            return $this->getModel()->newQuery()->where($columns)->first();
        });

        Assert::nullOrIsInstanceOf($model, ResourceInterface::class);

        return $model;
    }

    public function findUsingColumn(string $column, $value): Collection
    {
        return $this->rememberCache(static::class, __FUNCTION__, func_get_args(), function () use ($column, $value): Collection {
            /** @psalm-var Collection */
            return $this->getModel()->newQuery()->where($column, $value)->get();
        });
    }

    /**
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-suppress InvalidReturnStatement
     */
    public function findUsingColumns(array $columns)
    {
        return $this->rememberCache(static::class, __FUNCTION__, func_get_args(), function () use ($columns): Collection {
            /** @psalm-var Collection */
            return $this->getModel()->newQuery()->where($columns)->get();
        });
    }

    public function getTagName(): string
    {
        $class = str_replace('\\', '', get_class($this->getModel()));

        return Str::plural(
            Str::snake($class)
        );
    }
}
