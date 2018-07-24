<?php

declare(strict_types=1);

namespace Test\Scn\Hydrator;

use PHPUnit\Framework\TestCase;
use Scn\Hydrator\Configuration\ExtractorConfigInterface;
use Scn\Hydrator\Configuration\GenericExtractorConfig;
use Scn\Hydrator\Configuration\GenericHydratorConfig;
use Scn\Hydrator\Configuration\HydratorConfigInterface;
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

    /**
     * @var \Closure
     */
    private $testPropertySetter;

    /**
     * @var \Closure
     */
    private $testPropertyGetter;

    public function setUp(): void
    {
        $this->subject = new Hydrator();
        $this->testValueStorage = [];
        $this->testPropertySetter = function (
            $value,
            string $name
        ): void {
            $this->testValueStorage[$name] = $value;
        };
        $this->testPropertyGetter = function (string $name) {
            return $this->testValueStorage[$name];
        };
    }

    private function createHydratorConfig(...$propertyNames): HydratorConfigInterface
    {
        $setters = array_pad([], count($propertyNames), $this->testPropertySetter);

        return new GenericHydratorConfig(array_combine($propertyNames, $setters));
    }

    private function createExtractorConfig(...$propertyNames): ExtractorConfigInterface
    {
        $getters = array_pad([], count($propertyNames), $this->testPropertyGetter);

        return new GenericExtractorConfig(array_combine($propertyNames, $getters));
    }

    private function assertHydrationResult(array $expectedData): void
    {
        $this->assertSame($expectedData, $this->testValueStorage);
    }

    public function testHydrate(): void
    {
        $hydratorConfig = $this->createHydratorConfig('prop1', 'prop2', 'prop3');


        $randomInt = random_int(0, PHP_INT_MAX);
        $testData = [
            'prop1' => 'fu',
            'prop2' => $randomInt,
            'prop3' => null,
         ];

        $this->subject->hydrate($hydratorConfig, $this, $testData);

        $this->assertHydrationResult($testData);
    }

    public function testHydrateWithIgnoreKeys(): void
    {
        $hydratorConfig = $this->createHydratorConfig('prop1', 'prop2', 'prop3');

        $randomInt = random_int(0, PHP_INT_MAX);
        $testData = [
            'prop1' => 'fu',
            'prop2' => $randomInt,
            'prop3' => null,
        ];

        $this->subject->hydrate($hydratorConfig, $this, array_values($testData), Hydrator::IGNORE_KEYS);

        $this->assertHydrationResult($testData);
    }

    public function testHydrateIsStrictByDefault()
    {
        $hydratorConfig = $this->createHydratorConfig('prop1');

        $testData = [
            'unexpected' => true,
            'prop1' => 'fu',
            'another_unexpected' => true,
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unexpected data: unexpected, another_unexpected');

        $this->subject->hydrate($hydratorConfig, $this, $testData);
    }

    public function testHydrateOnNonStrict()
    {
        $hydratorConfig = $this->createHydratorConfig('prop1');

        $testData = [
            'unexpected' => true,
            'prop1' => 'fu',
            'another_unexpected' => true,
        ];

        $expectedResult = [
            'prop1' => 'fu',
        ];

        $this->subject->hydrate($hydratorConfig, $this, $testData, Hydrator::NO_STRICT_KEYS);
        $this->assertHydrationResult($expectedResult);
    }

    public function testExtract(): void
    {
        $extractorConfig = $this->createExtractorConfig('prop1', 'prop2', 'prop3');

        $randomInt = random_int(0, PHP_INT_MAX);

        $this->testValueStorage = [
            'prop1' => 'fu',
            'prop2' => $randomInt,
            'prop3' => null,
        ];

        $this->assertSame(
            $this->testValueStorage,
            $this->subject->extract($extractorConfig, $this)
        );
    }

    public function testExtractionResultEqualsHydrationData()
    {
        $propertyNames = ['prop1', 'prop2', 'prop3'];

        $hydratorConfig = $this->createHydratorConfig(...$propertyNames);
        $extractorConfig = $this->createExtractorConfig(...$propertyNames);

        $testData = array_combine($propertyNames, [
            random_int(0, PHP_INT_MAX),
            random_int(0, PHP_INT_MAX),
            random_int(0, PHP_INT_MAX),
        ]);

        $this->subject->hydrate($hydratorConfig, $this, $testData);
        $extractorResult = $this->subject->extract($extractorConfig, $this);

        $this->assertSame(
            $testData,
            $extractorResult
        );
    }
}
