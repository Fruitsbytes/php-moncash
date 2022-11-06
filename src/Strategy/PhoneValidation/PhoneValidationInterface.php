<?php

namespace Fruitsbytes\PHP\MonCash\Strategy\PhoneValidation;

interface PhoneValidationInterface
{

    /**
     * Check if all required dependencies and  configuration are available
     * @return bool
     * @throws PhoneValidationExeption
     */
    function check(): bool;

    /**
     * @param  array{ number: string|int, country: string, code: string}  $phone
     *
     * @return  array{ number: string|int, country: string, code: string}
     * @throws PhoneValidationExeption
     */
    static function parse(array $phone): array;


    /**
     * @param  array{ number: string|int, country: string, code: string}  $phone
     *
     * @return bool
     * @throws PhoneValidationExeption
     */
    static function isValid(array $phone): bool;

    /**
     * @param  array{ number: string|int, country: string, code: string}  $phone
     * @param  string| null  $format  E164, International , ....
     *
     * @return string
     * @throws PhoneValidationExeption
     */
    static function format(array $phone, ?string $format): string;
}
