<?php

namespace Piggly\Tests\Pix\Api\Entities;

use DateInterval;
use DateTime;
use PHPUnit\Framework\TestCase;
use Piggly\Pix\Api\Payloads\Cob;
use Piggly\Pix\Api\Payloads\Entities\Amount;
use Piggly\Pix\Api\Payloads\Entities\Calendar;
use Piggly\Pix\Api\Payloads\Entities\DueAmountModality;
use Piggly\Pix\Api\Payloads\Entities\Location;
use Piggly\Pix\Api\Payloads\Entities\Person;
use Piggly\Pix\Api\Payloads\Entities\Pix;
use Piggly\Pix\Api\Payloads\Entities\Refund;

/**
 * @coversDefaultClass \Piggly\Pix\Api\Payloads\Cob
 */
class CobTest extends TestCase
{
    /**
     * Assert if $payload is equals to $obj exported.
     *
     * Anytime it runs will create 100 random unique
     * payloads. It must assert all anytime.
     *
     * @covers ::import
     * @covers ::export
     * @dataProvider dataCobs
     * @test Expecting positive assertion.
     * @param array $payload
     * @param Cob $obj
     * @return void
     */
    public function isMatching(array $payload, Cob $obj)
    {
        $this->assertEquals($payload, $obj->export());
    }

    /**
     * A bunch of pixs to import to Cob payload.
     * Provider to isMatching() method.
     * Generated by fakerphp.
     * @return array
     */
    public function dataCobs(): array
    {
        $arr = [];
        $faker = \Faker\Factory::create('pt_BR');

        for ($i = 0; $i < 100; $i++) {
            $payload = [];
            $type = $faker->randomElement(Cob::TYPES);

            $payload['calendario'] = $this->_getCalendar($faker, $type)->export();

            if ($faker->boolean()) {
                $payload['recebedor'] = $this->_getPerson($faker)->export();
            }

            $payload['devedor'] = $this->_getPerson($faker)->export();
            $payload['valor'] = $this->_getAmount($faker, $type)->export();
            $payload['chave'] = $this->_getPixKey($faker);

            if ($faker->boolean()) {
                $payload['solicitacaoPagador'] = $faker->sentence(3);
            }

            if ($faker->boolean()) {
                $payload['revisao'] = 0;
            }

            if ($faker->boolean()) {
                $payload['infoAdicionais'] = [['nome'=>'random','valor'=>$faker->randomNumber()]];
            }

            if ($faker->boolean()) {
                $payload['txid'] = $faker->regexify('[0-9A-Za-z]{25}');
            }

            $payload['status'] = $faker->randomElement(Cob::STATUSES);

            if ($faker->boolean()) {
                $payload['loc'] = $this->_getLocation($faker, $type)->export();
            } else {
                $payload['loc'] = ['id' => 0];
            }

            if ($faker->boolean()) {
                $payload['pix'] = $this->_getPix($faker)->export();
            }

            $arr[] = [ $payload, (new Cob())->import($payload) ];
        }

        return $arr;
    }

    /**
     * Get a person.
     *
     * @param \Faker\Generator $faker
     * @return Person
     */
    private function _getPerson($faker): Person
    {
        return (new Person())
                    ->setDocument($faker->boolean() ? $faker->cpf() : $faker->cnpj())
                    ->setName($faker->firstName().' '.$faker->lastName());
    }

    /**
     * Get a location.
     *
     * @param \Faker\Generator $faker
     * @param string $cobType
     * @return Location
     */
    private function _getLocation($faker, $cobType): Location
    {
        return (new Location())
                    ->setId(0)
                    ->setLocation($faker->url())
                    ->setType($cobType)
                    ->setCreatedAt(new DateTime());
    }

    /**
     * Get a calendar.
     *
     * @param \Faker\Generator $faker
     * @param string $cobType
     * @return Calendar
     */
    private function _getCalendar($faker, $cobType): Calendar
    {
        if ($cobType === Cob::TYPE_IMMEDIATE) {
            return (new Calendar())->setCreatedAt(new DateTime())->setExpiresIn(3600);
        }

        return (new Calendar())
                    ->setCreatedAt(new DateTime())
                    ->setDueDate((new DateTime())->add(new DateInterval('P7D')))
                    ->setExpirationAfter(7);
    }

    /**
     * Get an amount with random modalities.
     *
     * @param \Faker\Generator $faker
     * @param string $cobType
     * @return Amount
     */
    private function _getAmount($faker, $cobType): Amount
    {
        if ($cobType === Cob::TYPE_IMMEDIATE) {
            return (new Amount())->setOriginal($faker->randomFloat(2, 1, 999));
        }

        return (new Amount())
                    ->setOriginal($faker->randomFloat(2, 1, 999))
                    ->addModality($this->_getModality($faker));
    }

    /**
     * Get a modality.
     *
     * @param \Faker\Generator $faker
     * @return DueAmountModality
     */
    private function _getModality($faker): DueAmountModality
    {
        $type = $faker->randomElement(DueAmountModality::MODALITIES);

        switch ($type) {
            case DueAmountModality::MODALITY_BANKFINE:
                $list = DueAmountModality::BANKFINE_MODALITIES;
                break;
            case DueAmountModality::MODALITY_DISCOUNT:
                $list = DueAmountModality::DISCOUNT_MODALITIES;
                break;
            case DueAmountModality::MODALITY_FEE:
                $list = DueAmountModality::FEE_MODALITIES;
                break;
            case DueAmountModality::MODALITY_REDUCTION:
                $list = DueAmountModality::REDUCTION_MODALITIES;
                break;
        }

        return (new DueAmountModality($type))
                    ->setId($faker->randomElement($list))
                    ->setAmount(\number_format($faker->randomFloat(2, 1, 999), 2, '.', ''));
    }

    /**
     * Get a pix with random refunds.
     *
     * @param \Faker\Generator $faker
     * @return Refund
     */
    private function _getPix($faker): Pix
    {
        $pix = new Pix();

        $pix
            ->setE2eid($faker->regexify('[0-9A-Za-z]{25}'))
            ->setAmount($faker->randomFloat(2, 1, 999))
            ->setProcessedAt(new DateTime());

        if ($faker->boolean()) {
            $random = $faker->numberBetween(1, 5);

            for ($j = 0; $j < $random; $j++) {
                $pix->addRefund($this->_getRefund($faker));
            }
        }

        return $pix;
    }

    /**
     * Get a refund.
     *
     * @param \Faker\Generator $faker
     * @return Refund
     */
    private function _getRefund($faker): Refund
    {
        return (new Refund())
                    ->setId($faker->regexify('[0-9A-Za-z]{25}'))
                    ->setRid($faker->regexify('[0-9A-Za-z]{25}'))
                    ->setAmount(\number_format($faker->randomFloat(2, 1, 999), 2, '.', ''))
                    ->setStatus($faker->randomElement(Refund::STATUSES));
    }

    /**
     * Get random pix key.
     *
     * @param \Faker\Generator $faker
     * @return string
     */
    protected function _getPixKey($faker): string
    {
        $num = $faker->numberBetween(0, 4);

        switch ($num) {
            case 0:
                return $faker->cnpj(false);
            case 1:
                return $faker->cpf(false);
            case 2:
                return $faker->email();
            case 3:
                return '+55'.$faker->phoneNumberCleared();
            case 4:
                return $this->_genUuid();
        }
    }

    /**
     * Generate random uuidv4.
     *
     * @return void
     */
    protected function _genUuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
