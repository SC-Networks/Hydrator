<?php

declare(strict_types=1);

namespace Scn\Hydrator;

use Scn\Hydrator\Configuration\ExtractorConfigInterface;
use Scn\Hydrator\Configuration\HydratorConfigInterface;

final class Hydrator implements HydratorInterface
{

    private function invoke(callable $callback, object $entity, ...$args)
    {
        if ($callback instanceof \Closure) {
            $callback = $callback->bindTo($entity, $entity);
        }

        return $callback(...$args);
    }

    public function hydrate(HydratorConfigInterface $config, object $entity, array $data): void
    {
        foreach ($config->getHydratorProperties() as $propertyName => $set) {
            $this->invoke($set, $entity, $data[$propertyName] ?? null, $propertyName);
        }
    }

    public function extract(ExtractorConfigInterface $config, object $entity): array
    {
        $data = [];

        foreach ($config->getExtractorProperties() as $propertyName => $get) {
            $data[$propertyName] = $this->invoke($get, $entity, $propertyName);
        }

        return $data;
    }
}
