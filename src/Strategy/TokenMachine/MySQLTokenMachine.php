<?php

namespace Fruitsbytes\PHP\MonCash\Strategy\TokenMachine;


class MySQLTokenMachine implements TokenMachineInterface
{
    public function __construct()
    {
        $this->check();
    }

    /**@inheritdoc */
    function check(): bool
    {
        // TODO: Implement check() method.
        return  false;
    }


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
