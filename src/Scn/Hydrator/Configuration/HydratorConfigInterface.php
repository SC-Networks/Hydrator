<?php

declare(strict_types=1);

namespace Scn\Hydrator\Configuration;

interface HydratorConfigInterface
{
    public function getHydratorProperties(): array;
}
