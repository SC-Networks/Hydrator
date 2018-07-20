<?php

declare(strict_types=1);

namespace Scn\Hydrator\Configuration;

interface ExtractorConfigInterface
{
    public function getExtractorProperties(): array;
}
