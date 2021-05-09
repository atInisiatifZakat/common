<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Concerns;

use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;
use Illuminate\Database\Eloquent\Model;

trait UuidPrimaryKey
{
    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public static function bootUuidPrimaryKey(): void
    {
        static::creating(function (Model $model): void {
            if (! isset($model->attributes[$model->getKeyName()])) {
                $model->incrementing = false;

                $uuid = Uuid::uuid6();

                $model->attributes[$model->getKeyName()] = $uuid->toString();
            }
        });
    }

    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    /**
     * @param null|string $id
     *
     * @psalm-suppress ImplementedParamTypeMismatch
     */
    public function setId($id): void
    {
        Assert::nullOrUuid($id);

        $this->setAttribute('id', $id);
    }
}
