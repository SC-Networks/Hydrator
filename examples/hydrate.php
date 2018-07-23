<?php

declare(strict_types=1);

use Scn\Hydrator\Configuration\HydratorConfigInterface;
use Scn\Hydrator\Hydrator;

require_once __DIR__.'/assets.php';

class CarHydrationConfig implements HydratorConfigInterface
{

    public function getHydratorProperties(): array
    {
        return [
            'name' => function (string $value): void {
                $this->name = $value;
            },
            'color' => function (string $value): void {
                $this->color = $value;
            },
            'number_of_wheels' => function (int $value): void {
                $this->numberOfWheels = $value;
            },
            'out_of_stock' => function (bool $value): void {
                $this->available = !$value;
            }
        ];
    }
}

$data = [
    'name' => 'Gaudi',
    'color' => 'pink',
    'number_of_wheels' => 5,
    'out_of_stock' => false,
];

$car = new Car(null, null, null, null); // create entity class
$hydrator = new Hydrator();

$hydratorConfig = new CarHydrationConfig();
$hydrator->hydrate($hydratorConfig, $car, $data);

var_dump($car);

/**
object(Car)#6 (4) {
    ["name":"Car":private]=>
    string(5) "Gaudi"
    ["color":"Car":private]=>
    string(4) "pink"
    ["numberOfWheels":"Car":private]=>
    int(5)
    ["available":"Car":private]=>
    bool(true)
}
 */
