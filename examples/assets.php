<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

final class Car
{
    public function __construct(
        private ?string $name,
        private ?string $color,
        private ?int $numberOfWheels,
        private ?bool $available
    ) {
    }
}
