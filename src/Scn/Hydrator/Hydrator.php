<?php

declare(strict_types=1);

namespace Scn\Hydrator;

use Scn\Hydrator\Configuration\ExtractorConfigInterface;
use Scn\Hydrator\Configuration\HydratorConfigInterface;

final class Hydrator implements HydratorInterface
{

    public const NO_STRICT_KEYS = 1;

    private function invoke(callable $callback, object $entity, ...$args)
    {
        if ($callback instanceof \Closure) {
            $callback = $callback->bindTo($entity, $entity);
        }

        return $callback(...$args);
    }

    public function hydrate(
        HydratorConfigInterface $config,
        object $entity,
        array $data,
        int $flags = 0
    ): void {
        $hydratorProperties = $config->getHydratorProperties();

        if (~$flags & static::NO_STRICT_KEYS) {
            $diff = array_keys(array_diff_key($data, $hydratorProperties));

            if ($diff !== []) {
                throw new \InvalidArgumentException(sprintf('Unexpected data: %s', join(', ', $diff)));
            }
        }

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
