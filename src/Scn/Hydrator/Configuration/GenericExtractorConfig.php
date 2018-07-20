<?php

declare(strict_types=1);

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

    public function getExtractorProperties(): array
    {
        return $this->properties;
    }
}
