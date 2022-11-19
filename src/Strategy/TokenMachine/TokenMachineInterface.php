<?php

namespace Fruitsbytes\PHP\MonCash\Strategy\TokenMachine;

use Fruitsbytes\PHP\MonCash\Strategy\StrategyInterface;

/**
 * Get and maybe cache an authentication API token.
 */
interface TokenMachineInterface extends StrategyInterface
{

    public function __construct();

    /**
     * @inheritdoc
     * @throws TokenMachineException
     */
    function check(): bool;

    /**
     * Get a usable token
     *
     * @param  bool  $new  if true get a fresh token do not tru to cache it
     *
     * @return string|bool
     */
    function getToken(bool $new = false): string|bool;

    /**
     * Checks if the token is usable.
     *
     * ⚠ This may or may not affect performance  and/or usage quotas
     *
     * @param  string  $token
     *
     * @return bool
     */
    function isTokenValid(string $token): bool;

    /**
     * Checks If the Token is expired
     *
     * @param  string  $token
     *
     * @return bool|int Returns false or remaining expiration time.
     */
    function isTokenExpired(string $token): bool|int;

}
