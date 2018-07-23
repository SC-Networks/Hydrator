<?php

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

    public function setUp()
    {
        $this->subject = new Hydrator();
        $this->testValueStorage = [];
    }

    public function testHydrate()
    {
        $testPropertySetter = function (
            $value,
            $name
        ) {
            $this->testValueStorage[$name] = $value;
        };

        $hydratorConfig = new GenericHydratorConfig([
            'prop1' => $testPropertySetter,
            'prop2' => $testPropertySetter,
            'prop3' => $testPropertySetter,
        ]);


        $randomInt = mt_rand(0, PHP_INT_MAX);
        $testData = [
            'prop1' => 'fu',
            'prop2' => $randomInt,
            'prop3' => null,
         ];

        $this->subject->hydrate($hydratorConfig, $this, $testData);

        $this->assertSame($testData, $this->testValueStorage);
    }

    public function testExtract()
    {
        $testPropertyGetter = function (
            $name
        ) {
            return $this->testValueStorage[$name];
        };

        $extractorConfig = new GenericExtractorConfig([
            'prop1' => $testPropertyGetter,
            'prop2' => $testPropertyGetter,
            'prop3' => $testPropertyGetter,
        ]);


        $randomInt = mt_rand(0, PHP_INT_MAX);

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
