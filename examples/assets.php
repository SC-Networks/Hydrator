<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

final class Car
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $color;

    /**
     * @var int
     */
    private $numberOfWheels;

    /**
     * @var bool
     */
    private $available;

    public function __construct(
        ?string $name,
        ?string $color,
        ?int $numberOfWheels,
        ?bool $available
    ) {
        $this->name = $name;
        $this->color = $color;
        $this->numberOfWheels = $numberOfWheels;
        $this->available = $available;
    }
}
