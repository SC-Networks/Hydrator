<?php

namespace Scn\Hydrator;

use Scn\Hydrator\Configuration\ExtractorConfigInterface;
use Scn\Hydrator\Configuration\HydratorConfigInterface;

final class Hydrator implements HydratorInterface
{

    /**
     * @param HydratorConfigInterface $config
     * @param object $entity
     * @param array $data
     *
     * @return void
     */
    public function hydrate(HydratorConfigInterface $config, $entity, array $data)
    {
        foreach ($config->getHydratorProperties() as $propertyName => $set) {
            if ($set instanceof \Closure) {
                $set = $set->bindTo($entity, $entity);
            }

            $set(isset($data[$propertyName]) ? $data[$propertyName] : null, $propertyName);
        }
    }

    /**
     * @param ExtractorConfigInterface $config
     * @param object $entity
     *
     * @return array
     */
    public function extract(ExtractorConfigInterface $config, $entity)
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
}
