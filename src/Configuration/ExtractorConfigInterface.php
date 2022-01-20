<?php

declare(strict_types=1);

namespace Scn\Hydrator\Configuration;

interface ExtractorConfigInterface
{
    /**
     * @return array<array-key, callable>
     */
    public function getExtractorProperties(): array;
}
