<?php

declare(strict_types=1);

namespace Scn\Hydrator\Configuration;

interface HydratorConfigInterface
{
    /**
     * @return array<array-key, callable>
     */
    public function getHydratorProperties(): array;
}
