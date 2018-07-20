<?php

declare(strict_types=1);

use Scn\Hydrator\Configuration\ExtractorConfigInterface;
use Scn\Hydrator\Hydrator;

require_once __DIR__.'/assets.php';

class CarExtractionConfig implements ExtractorConfigInterface
{

    public function getExtractorProperties(): array
    {
        return [
            'name' => function (): string {
                return $this->name;
            },
            'color' => function (): string {
                return $this->color;
            },
            'number_of_wheels' => function (): int {
                return $this->numberOfWheels;
            },
            'in_stock' => function (): bool {
                return !$this->available;
            }
        ];
    }
}

$car = new Car(
    'Gaudi',
    'pink',
    5,
    true
);

$hydrator = new Hydrator();

$extractorConfig = new CarExtractionConfig();

$data = $hydrator->extract($extractorConfig, $car);

var_dump($data);

/**
array(4) {
    ["name"]=>
    string(5) "Gaudi"
    ["color"]=>
    string(4) "pink"
    ["number_of_wheels"]=>
    int(5)
    ["in_stock"]=>
    bool(false)
}
 */
