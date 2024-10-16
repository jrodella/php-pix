<?php

namespace Piggly\Tests\Pix\Api\Entities;

use DateTime;
use PHPUnit\Framework\TestCase;
use Piggly\Pix\Api\Payloads\Cob;
use Piggly\Pix\Api\Payloads\Entities\Location;

/**
 * @coversDefaultClass \Piggly\Pix\Api\Payloads\Entities\Location
 */
class LocationTest extends TestCase
{
    /**
     * Assert if $payload is equals to $obj exported.
     *
     * Anytime it runs will create 100 random unique
     * payloads. It must assert all anytime.
     *
     * @covers ::import
     * @covers ::export
     * @dataProvider dataLocations
     * @test Expecting positive assertion.
     * @param array $payload
     * @param Location $obj
     * @return void
     */
    public function isMatching(array $payload, Location $obj)
    {
        $this->assertEquals($payload, $obj->export());
    }

    /**
     * Assert if $actual is equals to $expected.
     *
     * Anytime it runs will create 100 random unique
     * payloads. It must assert all anytime.
     *
     * @covers ::setCreatedAt
     * @covers ::getCreatedAt
     * @dataProvider dataFormats
     * @test Expecting positive assertion.
     * @param mixed $expected
     * @param mixed $actual
     * @return void
     */
    public function isMatchingFormat($expected, $actual)
    {
        $this->assertEquals($expected, $actual);
    }

    /**
     * A bunch of pixs to import to Location payload.
     * Provider to isMatching() method.
     * Generated by fakerphp.
     * @return array
     */
    public function dataLocations(): array
    {
        $arr = [];
        $faker = \Faker\Factory::create('pt_BR');

        for ($i = 0; $i < 100; $i++) {
            $payload = [
                'id' => $faker->numberBetween(1000, 9999)
            ];

            if ($faker->boolean()) {
                $payload['txid'] = $faker->regexify('[0-9A-Za-z]{25}');
            }

            $payload['location'] = $faker->url();
            $payload['tipoCob'] = $faker->randomElement(Cob::TYPES);
            $payload['criacao'] = $faker->dateTimeBetween('-1 week', '+1 week')->format(DateTime::RFC3339);

            $arr[] = [ $payload, (new Location())->import($payload) ];
        }

        return $arr;
    }

    /**
     * A bunch of locations to validate data.
     * Provider to isMatchingFormat() method.
     * Generated by fakerphp.
     * @return array
     */
    public function dataFormats(): array
    {
        $arr = [];
        $faker = \Faker\Factory::create('pt_BR');

        for ($i = 0; $i < 100; $i++) {
            $createdAt = $faker->dateTimeBetween('-1 week', '+1 week');

            $location = new Location();
            $location->setCreatedAt($createdAt->format(DateTime::RFC3339));

            $arr[] = [ $createdAt, $location->getCreatedAt() ];
        }

        return $arr;
    }
}
