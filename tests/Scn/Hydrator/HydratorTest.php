<?php

declare(strict_types=1);

namespace Test\Scn\Hydrator;

use PHPUnit\Framework\TestCase;
use Scn\Hydrator\Configuration\GenericExtractorConfig;
use Scn\Hydrator\Configuration\GenericHydratorConfig;
use Scn\Hydrator\Hydrator;

final class HydratorTest extends TestCase
{

    /**
     * @var Hydrator
     */
    private $subject;

    /**
     * @var array
     */
    private $testValueStorage = [];

    public function setUp(): void
    {
        $this->subject = new Hydrator();
        $this->testValueStorage = [];
    }

    public function testHydrate(): void
    {
        $testPropertySetter = function (
            $value,
            string $name
        ): void {
            $this->testValueStorage[$name] = $value;
        };

        $hydratorConfig = new GenericHydratorConfig([
            'prop1' => $testPropertySetter,
            'prop2' => $testPropertySetter,
            'prop3' => $testPropertySetter,
        ]);


        $randomInt = random_int(0, PHP_INT_MAX);
        $testData = [
            'prop1' => 'fu',
            'prop2' => $randomInt,
            'prop3' => null,
         ];

        $this->subject->hydrate($hydratorConfig, $this, $testData);

        $this->assertSame($testData, $this->testValueStorage);
    }

    public function testHydrateStrict()
    {
        $hydratorConfig = new GenericHydratorConfig([
            'prop1' => '',
        ]);


        $testData = [
            'unexpected' => true,
            'prop1' => 'fu',
            'another_unexpected' => true,
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unexpected data: unexpected, another_unexpected');

        $this->subject->hydrate($hydratorConfig, $this, $testData, Hydrator::STRICT_KEYS);
    }

    public function testExtract(): void
    {
        $testPropertyGetter = function (
            string $name
        ) {
            return $this->testValueStorage[$name];
        };

        $extractorConfig = new GenericExtractorConfig([
            'prop1' => $testPropertyGetter,
            'prop2' => $testPropertyGetter,
            'prop3' => $testPropertyGetter,
        ]);


        $randomInt = random_int(0, PHP_INT_MAX);


        $testData = [
            'prop1' => 'fu',
            'prop2' => $randomInt,
            'prop3' => null,
        ];

        $this->testValueStorage = [
            'prop1' => ['fu', $this],
            'prop2' => [$randomInt, $this],
            'prop3' => [null, $this],
        ];

        $this->assertSame(
            $this->testValueStorage,
            $this->subject->extract($extractorConfig, $this)
        );
    }
}
