<?php

namespace Fruitsbytes\PHP\Moncash\Strategy\PhoneValidation;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class LibPhoneValidation implements PhoneValidationInterface
{


    /**
     * @inheritDoc
     */
    public static function parse(array $phone): array
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $phone = $phoneUtil->parse($phone['number'], $phone['country']);

            return [
                "number"  => $phoneUtil->format($phone['number'], PhoneNumberFormat::E164) ?? null,
                "code"    => $phone->getCountryCode() ?? null,
                "country" => $country ?? null
            ];
        } catch (NumberParseException $e) {
            throw  new  PhoneValidationExeption('COULD_NOT_PARSE_PHONE_NUMBER');
        }
    }


    /**
     * @inheritDoc
     */
    public static function isValid(array $phone): bool
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $p = $phoneUtil->parse($phone['number'], $phone['country']);

            if ($p === null) {
                return false;
            } else {
                return $phoneUtil->isValidNumber($p);
            }
        } catch (NumberParseException $e) {
            throw  new  PhoneValidationExeption('COULD_NOT_bVERIFY_PHONE_NUMBER');
        }
    }

    /**
     * @inheritDoc
     */
    public static function format(array $phone, $format = PhoneNumberFormat::E164): string
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $p = $phoneUtil->parse($phone['number'], $phone['countru']);

            if ($p === null) {
                throw  new  PhoneValidationExeption('COULD_NOT_Format_PHONE_NUMBER');
            } else {
               return $phoneUtil->format($p, $format);
            }
        } catch (NumberParseException $e) {
            throw  new  PhoneValidationExeption('COULD_NOT_PARSE_PHONE_NUMBER');
        }

    }
}
