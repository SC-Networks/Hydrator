<?php

namespace Scn\Hydrator\Configuration;

final class GenericExtractorConfig implements ExtractorConfigInterface
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
    public function getExtractorProperties()
    {
        return $this->properties;
    }
}
