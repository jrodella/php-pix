<?php

namespace Piggly\Tests\Pix\Api\Entities;

use DateTime;
use PHPUnit\Framework\TestCase;
use Piggly\Pix\Api\Payloads\Entities\Calendar;

/**
 * @coversDefaultClass \Piggly\Pix\Api\Payloads\Entities\Calendar
 */
class CalendarTest extends TestCase
{
    /**
     * Assert if $payload is equals to $obj exported.
     *
     * Anytime it runs will create 100 random unique
     * payloads. It must assert all anytime.
     *
     * @covers ::import
     * @covers ::export
     * @dataProvider dataCalendars
     * @test Expecting positive assertion.
     * @param array $payload
     * @param Calendar $obj
     * @return void
     */
    public function isMatching(array $payload, Calendar $obj)
    {
        $this->assertEquals($payload, $obj->export());
    }

    /**
     * Assert if $actual is equals to $expected.
     *
     * Anytime it runs will create 100 random unique
     * payloads. It must assert all anytime.
     *
     * @covers ::setDocument
     * @covers ::getDocumentType
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
     * A bunch of pixs to import to Calendar payload.
     * Provider to isMatching() method.
     * Generated by fakerphp.
     * @return array
     */
    public function dataCalendars(): array
    {
        $arr = [];
        $faker = \Faker\Factory::create('pt_BR');

        for ($i = 0; $i < 100; $i++) {
            $payload = [];

            if ($faker->boolean()) {
                $array['criacao'] = $faker->dateTimeBetween('-1 week', '+1 week')->format(DateTime::RFC3339);
            }

            if ($faker->boolean()) {
                $array['apresentacao'] = $faker->dateTimeBetween('-1 week', '+1 week')->format(DateTime::RFC3339);
            }

            if ($faker->boolean()) {
                $array['expiracao'] = $faker->numberBetween(60, 3600);
            }

            if ($faker->boolean()) {
                $array['dataDeVencimento'] = $faker->dateTimeBetween('-1 week', '+1 week')->format('Y-m-d');
            }

            if ($faker->boolean()) {
                $array['validadeAposVencimento'] = $faker->numberBetween(5, 30);
            }

            $arr[] = [ $payload, (new Calendar())->import($payload) ];
        }

        return $arr;
    }

    /**
     * A bunch of calendars to validate data.
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
            $presentedAt = $faker->dateTimeBetween('-1 week', '+1 week');
            $dueDate = new DateTime($faker->dateTimeBetween('-1 week', '+1 week')->format('Y-m-d'));

            $calendar = new Calendar();
            $calendar
                ->setCreatedAt($createdAt->format(DateTime::RFC3339))
                ->setPresentedAt($presentedAt->format(DateTime::RFC3339))
                ->setDueDate($dueDate->format('Y-m-d'));

            $arr[] = [ $createdAt, $calendar->getCreatedAt() ];
            $arr[] = [ $presentedAt, $calendar->getPresentedAt() ];
            $arr[] = [ $dueDate, $calendar->getDueDate() ];
        }

        return $arr;
    }
}
