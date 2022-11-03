<?php

namespace Fruitsbytes\PHP\Moncash\Strategy\TokenMachine;

class FileTokenMachine implements TokenMachineInterface
{

    /** @inheritdoc  */
    function getToken(bool $new = false): string
    {
        // TODO: Implement getToken() method.
        return '';
    }

    /** @inheritdoc  */
    function isTokenValid(string $token): bool
    {
        // TODO: Implement isTokenValid() method.
        return false;
    }

    /** @inheritdoc  */
    function isTokenExpired(string $token): bool|int
    {
        // TODO: Implement isTokenExpired() method.
        return false;
    }
}
