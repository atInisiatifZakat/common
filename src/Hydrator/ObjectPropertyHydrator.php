<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Hydrator;

use ReflectionClass;
use ReflectionProperty;
use Laminas\Hydrator\AbstractHydrator;

final class ObjectPropertyHydrator extends AbstractHydrator
{
    /**
     * @var (null|array)[] indexed by class name and then property name
     */
    private static $skippedPropertiesCache = [];

    /**
     * Extracts the accessible non-static properties of the given $object.
     *
     * {@inheritDoc}
     */
    public function extract(object $object): array
    {
        $data = get_object_vars($object);
        $filter = $this->getFilter();

        foreach ($data as $name => $value) {
            // Filter keys, removing any we don't want
            if (! $filter->filter($name)) {
                unset($data[$name]);
                continue;
            }

            // Replace name if extracted differ
            $extracted = $this->extractName($name, $object);

            if ($extracted !== $name) {
                unset($data[$name]);
                $name = $extracted;
            }

            $data[$name] = $this->extractValue($name, $value, $object);
        }

        return $data;
    }

    /**
     * Hydrate an object by populating public properties
     *
     * Hydrates an object by setting public properties of the object.
     *
     * {@inheritDoc}
     *
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function hydrate(array $data, object $object)
    {
        /** @noinspection PhpPassByRefInspection */
        $properties = &self::$skippedPropertiesCache[get_class($object)] ?? null;

        if ($properties === null) {
            $reflection = new ReflectionClass($object);
            $properties = array_fill_keys(
                array_map(
                    function (ReflectionProperty $property) {
                        return $property->getName();
                    },
                    $reflection->getProperties(
                        ReflectionProperty::IS_PRIVATE
                        + ReflectionProperty::IS_PROTECTED
                        + ReflectionProperty::IS_STATIC
                    )
                ),
                true
            );
        }

        foreach ($data as $name => $value) {
            $property = $this->hydrateName($name, $data);

            if (isset($properties[$property])) {
                continue;
            }

            if (property_exists($object, $property)) {
                $object->{$property} = $this->hydrateValue($property, $value, $data);
            }
        }

        return $object;
    }
}
