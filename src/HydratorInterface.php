<?php

declare(strict_types=1);

namespace Scn\Hydrator;

use Scn\Hydrator\Configuration\ExtractorConfigInterface;
use Scn\Hydrator\Configuration\HydratorConfigInterface;

interface HydratorInterface
{
    public function hydrate(
        HydratorConfigInterface $config,
        object $entity,
        array $data,
        int $flags = Hydrator::DEFAULT,
    ): void;

    public function extract(
        ExtractorConfigInterface $config,
        object $entity,
    ): array;
}
