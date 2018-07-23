<?php

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

    /**
     * Car constructor.
     *
     * @param null|string $name
     * @param null|string $color
     * @param int|null $numberOfWheels
     * @param bool|null $available
     */
    public function __construct(
        $name,
        $color,
        $numberOfWheels,
        $available
    ) {
        $this->name = $name;
        $this->color = $color;
        $this->numberOfWheels = $numberOfWheels;
        $this->available = $available;
    }
}
