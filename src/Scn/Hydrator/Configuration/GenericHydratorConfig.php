<?php

declare(strict_types=1);

namespace Scn\Hydrator\Configuration;

final class GenericHydratorConfig implements HydratorConfigInterface
{

    /**
     * @var array<array-key, callable>
     */
    private array $properties;

    /**
     * @param array<array-key, callable> $propertyMapping
     */
    public function __construct(array $propertyMapping)
    {
        $this->properties = $propertyMapping;
    }

    /**
     * @return array<array-key, callable>
     */
    public function getHydratorProperties(): array
    {
        return $this->properties;
    }
}
