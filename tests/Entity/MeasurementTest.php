<?php

namespace App\Tests\Entity;

use App\Entity\Measurements;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class MeasurementTest extends TestCase
{
    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['0.5', '32.9'],
            ['6', '42.8'],
            ['7', '44.6'],
            ['30', '86'],
            ['10', '50'],
            ['1', '33.8'],
            ['-35', '-31'],
        ];
    }

    #[DataProvider('dataGetFahrenheit')]

    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $measurement = new Measurements();

        $measurement->setCelsius($celsius);

        $this->assertEquals($expectedFahrenheit, $measurement->getFahrenheit());
    }
}
