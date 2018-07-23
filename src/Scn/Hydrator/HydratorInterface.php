<?php

namespace Scn\Hydrator;

use Scn\Hydrator\Configuration\ExtractorConfigInterface;
use Scn\Hydrator\Configuration\HydratorConfigInterface;

interface HydratorInterface
{

    /**
     * @param HydratorConfigInterface $config
     * @param object $entity
     * @param array $data
     *
     * @return void
     */
    public function hydrate(HydratorConfigInterface $config, $entity, array $data);

    /**
     * @param ExtractorConfigInterface $config
     * @param object $entity
     *
     * @return array
     */
    public function extract(ExtractorConfigInterface $config, $entity);
}
