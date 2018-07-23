<?php

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

    /**
     * @return array
     */
    public function getHydratorProperties()
    {
        return $this->properties;
    }
}
