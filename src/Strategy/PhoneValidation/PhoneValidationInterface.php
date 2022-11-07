<?php

namespace Fruitsbytes\PHP\MonCash\Strategy\PhoneValidation;

use Fruitsbytes\PHP\MonCash\Strategy\StrategyInterface;

interface PhoneValidationInterface extends StrategyInterface
{

    /**
     * @inheritdoc
     * @throws PhoneValidationException
     */
    function check(): bool;

    /**
     * @param  array{ number: string|int, country: string, code: string}  $phone
     *
     * @return  array{ number: string|int, country: string, code: string}
     * @throws PhoneValidationException
     */
    static function parse(array $phone): array;


    /**
     * @param  array{ number: string|int, country: string, code: string}  $phone
     *
     * @return bool
     * @throws PhoneValidationException
     */
    static function isValid(array $phone): bool;

    /**
     * @param  array{ number: string|int, country: string, code: string}  $phone
     * @param  string| null  $format  E164, International , ....
     *
     * @return string
     * @throws PhoneValidationException
     */
    static function format(array $phone, ?string $format): string;
}
