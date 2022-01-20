<?php

declare(strict_types=1);

namespace Scn\Hydrator\Configuration;

final class GenericExtractorConfig implements ExtractorConfigInterface
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
    public function getExtractorProperties(): array
    {
        return $this->properties;
    }
}
