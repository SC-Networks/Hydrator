<?php

declare(strict_types=1);

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


$car = new Car(null, null, null, null); // create entity class
$hydrator = new Hydrator();

$hydratorConfig = new GenericHydratorConfig($mapping);
$hydrator->hydrate($hydratorConfig, $car, $data);

var_dump($car);

/*
class Car#6 (4) {
  private ?string $name =>
  string(5) "Gaudi"
  private ?string $color =>
  string(4) "pink"
  private ?int $numberOfWheels =>
  int(5)
  private ?bool $available =>
  bool(true)
}
 */