<?php

namespace Fruitsbytes\PHP\MonCash\Strategy\OrderIdGenerator;

use Fruitsbytes\PHP\MonCash\Strategy\StrategyInterface;
use Fruitsbytes\PHP\MonCash\Task;

interface OrderIdGeneratorInterface extends StrategyInterface
{

    /**
     * @throws  OrderIdGeneratorException
     */
    function __construct();

    /**
     * Check if all required dependencies and  configuration are available
     * @return bool
     * @throws OrderIdGeneratorException
     */
    function check(): bool;

    /**
     * Get a new  uniq idempotence identifier. Uniqueness is hard to achieve and many solutions allow for a small
     * chance of global collision between. The nature of the API would also increase the entropy:
     *  - Each idempotence ID would be evaluated at the client-business level,
     *     meaning 2 different business even in the same client account are technically allowed to use the same ID.
     *  - Each ID is linked to a transaction and most likely the sever will recycle it after a relevant TTL.
     *
     * It is important to mitigate with the management team on what would be the best way to handle eventual collisions.
     *
     * @param  bool  $log  Whether to persiste the ID, so it can be checked for availability later
     *
     * @return string
     * @throws OrderIdGeneratorException
     */
    function getNewID(bool $log = true): string;

    /**
     * Manually persiste an ID, so it can be checked for availability later.
     *
     * @param  string  $id
     *
     * @return bool
     */
    function logID(string $id): bool;

    /**
     * Check if the id is available to use
     *
     * @param  string  $id
     *
     * @return bool
     * @throws OrderIdGeneratorException
     */
    function isIDAvailable(string $id): bool;

    /**
     * Remove all expired tokens
     *
     * @param  int  $ttl  Time to live of the ID in the logs , the function should purge all expired IDs.
     *                   The expiration TTL should allow the associated server operation to no longer be
     *                    relevant in the MonCash API
     *
     * @return int The number of Ids recycled
     * @throws OrderIdGeneratorException
     */
    function cleanLog(int $ttl): int;

    /**
     * @return Task
     */
    function programCleaningTask(): Task;

}
