<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Contracts;

use Illuminate\Support\Collection;

interface ModelRepositoryInterface
{
    public function save(ResourceInterface $resource): bool;

    public function delete($id): void;

    public function findUsingId($id);

    public function findOneUsingColumn(string $column, $value);

    public function findOneUsingColumns(array $columns);

    public function findUsingColumn(string $column, $value): Collection;

    public function findUsingColumns(array $columns);
}
