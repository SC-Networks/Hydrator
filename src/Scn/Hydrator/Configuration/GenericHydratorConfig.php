<?php

declare(strict_types=1);

namespace Scn\Hydrator\Configuration;

final class GenericHydratorConfig implements HydratorConfigInterface
{

    /**
     * @var array
     */
    private $properties;

    public function __construct(array $propertyMapping)
    {
        $this->properties = $propertyMapping;
    }

    public function getHydratorProperties(): array
    {
        return $this->properties;
    }
}
