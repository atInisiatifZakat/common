<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common\Tests\Hydrator;

use PHPUnit\Framework\TestCase;
use Inisiatif\Package\Common\Hydrator\ObjectPropertyHydrator;
use Laminas\Hydrator\ObjectPropertyHydrator as LaminasObjectPropertyHydrator;

final class ObjectPropertyHydratorTest extends TestCase
{
    public function test_cannot_hydrate_not_exists_property(): void
    {
        $hydrator = new ObjectPropertyHydrator();

        $data = [
            'fooBar' => 'baz',
            'bazBar' => 'foo',
        ];

        $foo = new class()
        {
            public string $fooBar;

            public bool  $boolean = true;
        };

        $hydrator->hydrate($data, $foo);

        $this->assertFalse(property_exists($foo, 'bazBar'));
        $this->assertTrue($foo->boolean);
    }

    public function test_can_hydrate_not_exists_property(): void
    {
        $hydrator = new LaminasObjectPropertyHydrator();

        $data = [
            'fooBar' => 'baz',
            'bazBar' => 'foo',
        ];

        $foo = new class()
        {
            public string $fooBar;
        };

        $hydrator->hydrate($data, $foo);

        $this->assertTrue(property_exists($foo, 'bazBar'));
    }
}
