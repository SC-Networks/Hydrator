<?php

declare(strict_types=1);

namespace Scn\Hydrator;

use Closure;
use InvalidArgumentException;
use Scn\Hydrator\Configuration\ExtractorConfigInterface;
use Scn\Hydrator\Configuration\HydratorConfigInterface;

final class Hydrator implements HydratorInterface
{
    public const NO_STRICT_KEYS = 0b00000001;
    public const IGNORE_KEYS = 0b00000010;
    public const DEFAULT = 0b00000000;

    private function invoke(
        callable $callback,
        object $entity,
        mixed ...$args,
    ): mixed
    {
        if ($callback instanceof Closure) {
            $callback = $callback->bindTo($entity, $entity) ?: $callback;
        }

        return $callback(...$args);
    }

    public function hydrate(
        HydratorConfigInterface $config,
        object $entity,
        array $data,
        int $flags = self::DEFAULT,
    ): void {
        $hydratorProperties = $config->getHydratorProperties();

        if ($flags & self::IGNORE_KEYS) {
            $data = array_combine(array_keys($hydratorProperties), $data);
        }

        if (~$flags & self::NO_STRICT_KEYS) {
            $diff = array_keys(array_diff_key($data, $hydratorProperties));

            if ($diff !== []) {
                throw new InvalidArgumentException(sprintf('Unexpected data: %s', join(', ', $diff)));
            }
        }

        foreach ($hydratorProperties as $propertyName => $set) {
            $this->invoke($set, $entity, $data[$propertyName] ?? null, $propertyName);
        }
    }

    public function extract(ExtractorConfigInterface $config, object $entity): array
    {
        $data = [];

        foreach ($config->getExtractorProperties() as $propertyName => $get) {
            /** @psalm-suppress MixedAssignment */
            $data[$propertyName] = $this->invoke($get, $entity, $propertyName);
        }

        return $data;
    }
}
