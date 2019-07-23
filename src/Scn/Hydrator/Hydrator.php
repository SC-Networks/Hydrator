<?php

declare(strict_types=1);

namespace Scn\Hydrator;

use Scn\Hydrator\Configuration\ExtractorConfigInterface;
use Scn\Hydrator\Configuration\HydratorConfigInterface;

final class Hydrator implements HydratorInterface
{

    public const NO_STRICT_KEYS = 0b00000001;
    public const IGNORE_KEYS =    0b00000010;
    public const DEFAULT =        0b00000000;

    private function invoke(callable $callback, object $entity, ...$args)
    {
        if ($callback instanceof \Closure) {
            $callback = $callback->bindTo($entity, $entity);
        }

        return call_user_func_array($callback, $args);
    }

    private function arrayCombine(array $keys, array $data): array
    {
        $result = [];

        foreach (array_values($data) as $index => $value) {
            $result[$keys[$index] ?? (string) $index] = $value;
        }

        return $result;
    }

    public function hydrate(
        HydratorConfigInterface $config,
        object $entity,
        array $data,
        int $flags = self::DEFAULT
    ): void {
        $hydratorProperties = $config->getHydratorProperties();

        if ($flags & static::IGNORE_KEYS) {
            $data = $this->arrayCombine(array_keys($hydratorProperties), $data);
        }

        if (~$flags & static::NO_STRICT_KEYS) {
            $diff = array_keys(array_diff_key($data, $hydratorProperties));

            if ($diff !== []) {
                throw new \InvalidArgumentException(sprintf('Unexpected data: %s', join(', ', $diff)));
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
            $data[$propertyName] = $this->invoke($get, $entity, $propertyName);
        }

        return $data;
    }
}
