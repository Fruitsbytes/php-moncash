<?php

namespace Fruitsbytes\PHP\Moncash\Strategy\PhoneValidation;

use libphonenumber\PhoneNumberFormat;

class DefaultHaitianPhoneValidation extends LibPhoneValidation implements PhoneValidationInterface
{
    public static function parse(array $phone): array
    {
        return parent::parse(["number" => $phone['number'], "country" => 'HT']);
    }

    public static function isValid(array $phone): bool
    {
        return parent::isValid(['number' => $phone['number'], "country" => "HT"]);
    }

    public static function format(array $phone, $format = PhoneNumberFormat::E164): string
    {
        return parent::format(['number' => $phone['number'], "country" => "HT"], $format);
    }
}
