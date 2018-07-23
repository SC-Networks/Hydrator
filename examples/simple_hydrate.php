<?php

use Scn\Hydrator\Configuration\GenericHydratorConfig;
use Scn\Hydrator\Hydrator;

require_once __DIR__.'/assets.php';

$data = [
    'name' => 'Gaudi',
    'color' => 'pink',
    'number_of_wheels' => 5,
    'out_of_stock' => false,
];

$mapping = [
    'name' => function ($value) {
        $this->name = $value;
    },
    'color' => function ($value) {
        $this->color = $value;
    },
    'number_of_wheels' => function ($value) {
        $this->numberOfWheels = $value;
    },
    'out_of_stock' => function ($value) {
        $this->available = !$value;
    }
];


$car = new Car(null, null, null, null); // create entity class
$hydrator = new Hydrator();

$hydratorConfig = new GenericHydratorConfig($mapping);
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
