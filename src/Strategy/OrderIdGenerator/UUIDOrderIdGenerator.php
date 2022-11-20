<?php

namespace Fruitsbytes\PHP\MonCash\Strategy\OrderIdGenerator;

use Exception;
use Fruitsbytes\PHP\MonCash\API\Retry;
use Ramsey\Uuid\Uuid;

/**
 * Using [ramsey/uuid](https://uuid.ramsey.dev/) a PHP library for generating and working
 * with [RFC 4122](https://tools.ietf.org/html/rfc4122) version 1 to 7 universally unique identifiers (UUID).
 *
 */
class UUIDOrderIdGenerator extends SimpleOrderIdGenerator implements OrderIdGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function check(bool $thorough = false): bool
    {

        /**
         * Check if all required PHP functions are available.
         * - Some Host may be using a trimmed down version of PHP.
         * - In the future one of the function may be deprecated/removed
         */
        foreach (['touch', 'unlink', 'filectime', 'strtotime'] as $fn) {
            if (function_exists($fn) === false) {
                throw new OrderIdGeneratorException("Missing required function $fn");
            }
        }

        if (class_exists('Ramsey\Uuid\Uuid') === false) {
            throw new OrderIdGeneratorException("Could not find package ramsey/uuid");
        }

        try {
            $this->logID('monCash_file_test');
        } catch (Exception $e) {
            throw new OrderIdGeneratorException("Could not write to temp folder.");
        }

        return true;
    }

    /**
     * @inheritDoc
     *
     * @param  bool  $verifyLocally  If true checks the logs to see if it was already used
     * @param  int|string  $version  RFC 4122 version, either  fro uniqueness
     */
    public function getNewID(
        bool $log = false,
        bool $verifyLocally = false,
    ): string {

        $id = '';

        try {
            $retry = new Retry(function () use ($verifyLocally, $log, &$id) {
                $found = (string) Uuid::uuid4();

                $path = UUIDOrderIdGenerator::nameToPath($found);

                if ($verifyLocally && file_exists($path)) {
                    throw new OrderIdGeneratorException('ID already used.');
                }
                if ($log && ! $this->logID($found)) {
                    throw new OrderIdGeneratorException('Could not log ID.');
                }

                $id = $found;

                return true;
            });

            $retry->call();
        } catch (Exception $e) {
            throw new OrderIdGeneratorException('Error while generating ID.', 0, $e);
        }


        return $id;
    }
}
