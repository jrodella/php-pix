<?php

namespace Piggly\Tests\Pix\Emv;

use PHPUnit\Framework\TestCase;
use Piggly\Pix\Emv\Field;
use Piggly\Pix\Exceptions\InvalidEmvFieldException;

/**
 * @coversDefaultClass \Piggly\Pix\Emv\Field
 */
class AbstractFieldTest extends TestCase
{
    /**
     * Assert if $code is equals to $obj exported.
     *
     * Anytime it runs will create 100 random unique
     * payloads. It must assert all anytime.
     *
     * @covers ::export
     * @dataProvider dataFields
     * @test Expecting positive assertion.
     * @param string $code
     * @param Field $obj
     * @return void
     */
    public function isExportable(string $code, Field $obj)
    {
        $this->assertEquals($code, $obj->export());
    }

    /**
     * Assert if throw an exception.
     *
     * @covers ::required
     * @covers ::export
     * @test Expecting positive assertion.
     * @return void
     */
    public function throwRequiredException()
    {
        $this->expectException(InvalidEmvFieldException::class);
        (new Field())->required(true)->export();
    }

    /**
     * A bunch of fields to export.
     * Provider to isMatching() method.
     * Generated by fakerphp.
     * @return array
     */
    public function dataFields(): array
    {
        $arr = [];
        $faker = \Faker\Factory::create('pt_BR');

        for ($i = 0; $i < 100; $i++) {
            $id = \str_pad(\strlen($faker->numberBetween(0, 99)), 2, '0', STR_PAD_LEFT);
            $size = $faker->numberBetween(25, 50);

            $field = new Field(
                $id,
                $faker->word(),
                $size,
                $faker->boolean()
            );

            $len   = $faker->boolean() ? $size : $faker->numberBetween(0, 99);
            $value = $faker->regexify('[0-9A-Za-z]{'.$size.'}');
            $field->setValue($value);

            $size  = \str_pad($size, 2, '0', STR_PAD_LEFT);
            $value = $len > $size ? \substr($value, 0, $size) : $value;
            $arr[] = [ $id.$size.$value, $field ];
        }

        return $arr;
    }
}
