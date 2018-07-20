<?php

declare(strict_types=1);

namespace Scn\Hydrator;

use Scn\Hydrator\Configuration\ExtractorConfigInterface;
use Scn\Hydrator\Configuration\HydratorConfigInterface;

final class Hydrator implements HydratorInterface
{

    public function hydrate(HydratorConfigInterface $config, object $entity, array $data): void
    {
        foreach ($config->getHydratorProperties() as $propertyName => $set) {
            if ($set instanceof \Closure) {
                $set = $set->bindTo($entity, $entity);
            }

            $set($data[$propertyName] ?? null, $propertyName);
        }
    }

    public function extract(ExtractorConfigInterface $config, object $entity): array
    {
        $data = [];

        /**
         * @var string $propertyName
         * @var \Closure $get
         */
        foreach ($config->getExtractorProperties() as $propertyName => $get) {
            if (!$get instanceof \Closure) {
                throw new \RuntimeException('Must be closure');
            }

            $get = $get->bindTo($entity, $entity);
            $data[$propertyName] = $get($propertyName);
        }

        return $data;
    }

    public function postExtract(ExtractorConfigInterface $config, object $entity): void
    {
        if ($postExtractor = $config->getPostExtractCallback()) {
            $postExtractor = $postExtractor->bindTo($entity, $entity);
            $postExtractor();
        }
    }

    public function postHydrate(HydratorConfigInterface $config, object $entity): void
    {
        if ($postHydrator = $config->getPostHydrateCallback()) {
            $postHydrator = $postHydrator->bindTo($entity, $entity);
            $postHydrator();
        }
    }
}
