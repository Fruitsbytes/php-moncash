<?php

namespace Fruitsbytes\PHP\Moncash\Strategy\TokenMachine;

/**
 * Get and maybe cache an authentication API token.
 */
interface TokenMachineInterface
{

    /**
     * Check if all required dependencies and  configuration are available
     * @return bool|TokenMachineException
     */
    function check(): bool|TokenMachineException;

    /**
     * Get a usable token
     *
     * @param  bool  $new  if true get a fresh token do not tru to cache it
     *
     * @return string
     */
    function getToken(bool $new = false): string;

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
