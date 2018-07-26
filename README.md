# scn/hydrator

[![Latest Stable Version](https://poser.pugx.org/scn/hydrator/v/stable)](https://packagist.org/packages/scn/hydrator)
[![Monthly Downloads](https://poser.pugx.org/scn/hydrator/d/monthly)](https://packagist.org/packages/scn/hydrator)
[![License](https://poser.pugx.org/scn/hydrator/license)](LICENSE)
[![Build Status](https://travis-ci.org/SC-Networks/Hydrator.svg?branch=master)](https://travis-ci.org/SC-Networks/Hydrator)
[![Coverage](https://codecov.io/gh/SC-Networks/Hydrator/branch/master/graph/badge.svg)](https://codecov.io/gh/SC-Networks/Hydrator)

## A pragmatic hydrator and extractor library

### Installation

Via Composer:
```
$ composer require scn/hydrator
```

### Usage

See the `examples` folder for usage instructions by code.

#### Extraction

First of all you'll need a class that implements `\Scn\Hydrator\Config\ExtractorConfigInterface`.
The only method `getExtractorProperties` has to return an array with string keys and callables as values.

The string keys of this array are used to craft the result of the extraction.

The corresponding callables must have the signature `callable(string $propertyName): mixed`. The `$propertyName`
parameter will be the corresponding string key. You will usually not need this information.

If this callable is an instance of `\Closure`, its' `$this` and `static` context are bound to the entity object to 
extract. As a result of this the closure will have access to private and protected properties and methods of the entity.

A short example:

```php
<?php

require_once 'vendor/autoload.php';

class ExtractorConfig implements \Scn\Hydrator\Configuration\ExtractorConfigInterface
{

    public function getExtractorProperties(): array
    {
        return [
            'property' => function (string $propertyName): string {
                return $this->privateProperty; // `$this` will be the entity to extract
            }
        ];
    }
}

class Entity
{
    private $privateProperty = 'private value';
}

$hydrator = new \Scn\Hydrator\Hydrator();

$result = $hydrator->extract(
    new ExtractorConfig(),
    new Entity()
);

var_dump(assert($result === ['property' => 'private value'])); // -> bool(true)

```

### Hydration

For hydration you'll need a class implementing `\Scn\Hydrator\Configuration\HydratorConfigInterface`.
The only method `getHydratorProperties` has to return an array with string keys and callables as values.

As an alternative you can use the `\Scn\Hydrator\Configuration\GenericHydratorConfig`.

The string keys have to correspond the keys in the data to hydrate your object with.

The corresponding callables must have the signature `callable(mixed $value, string $propertyName): void`.
The `$propertyName` parameter will be the corresponding string key. You will usually not need this information.

If this callable is an instance of `\Closure`, its' `$this` and `static` context are bound to the entity object to 
hydrate. As a result of this the closure will have access to private and protected properties and methods of the entity.

A short example:

```php
<?php

require_once __DIR__.'vendor/autoload.php';

class HydratorConfig implements \Scn\Hydrator\Configuration\HydratorConfigInterface
{

    public function getHydratorProperties(): array
    {
        return [
            'property' => function (string $value, string $propertyName): void {
                $this->privateProperty = $value; // $this will be the entity to hydrate
            }
        ];
    }
}

class Entity
{
    private $privateProperty = 'private value';

    public function getPropertyValue(): string
    {
        return $this->privateProperty;
    }
}

$hydrator = new \Scn\Hydrator\Hydrator();
$data = [
    'property' => 'hydrated private value',
];

$entity = new Entity();

$hydrator->hydrate(
    new HydratorConfig(), 
    $entity, 
    $data
);

var_dump(assert('hydrated private value' === $entity->getPropertyValue())); // -> bool(true)
```

#### Hydration flags

The `hydrate` method has a fourth, optional bit flag parameter `$flags`.

Currently there are two options available

##### `\Scn\Hydrator\Hydrator::NO_STRICT_KEYS` (1)

If this bit is set in `$flags`, the hydrator will ignore keys in the data array that have no corresponding key in the array
the `getHydratorProperties` method returns.

This bit is not set by default, additional keys in the data array will lead to an `\InvalidArgumentException` exception thrown by the hydrator.

##### `\Scn\Hydrator\Hydrator::IGNORE_KEYS` (2)

If this bit is set, the hydrator will ignore the keys of the data array but assume the entries in the data array are in
the same order as in the hydrator configuration array.

Example:

```php
<?php

use Scn\Hydrator\Hydrator;

require_once __DIR__.'vendor/autoload.php';

class HydratorConfig implements \Scn\Hydrator\Configuration\HydratorConfigInterface
{

    public function getHydratorProperties(): array
    {
        return [
            'property_a' => function (string $value, string $propertyName): void {
                $this->privatePropertyA = $value;
            },
            'property_b' => function (string $value, string $propertyName): void {
                $this->privatePropertyB = $value;
            },
            'property_c' => function (string $value, string $propertyName): void {
                $this->privatePropertyC = $value;
            },

        ];
    }
}

class Entity
{
    private $privatePropertyA;
    private $privatePropertyB;
    private $privatePropertyC;

    public function getPropertyValues(): array
    {
        return [
            $this->privatePropertyA,
            $this->privatePropertyB,
            $this->privatePropertyC,
        ];
    }
}

$hydrator = new \Scn\Hydrator\Hydrator();
$data = ['value a', 'value_b', 'value_c'];

$entity = new Entity();

$hydrator->hydrate(
    new HydratorConfig(),
    $entity,
    $data,
    Hydrator::IGNORE_KEYS
);

var_dump(assert($data === $entity->getPropertyValues())); // -> bool(true)
```
