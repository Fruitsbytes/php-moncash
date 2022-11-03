<?php

namespace Fruitsbytes\PHP\Moncash\Strategy\IdempotenceKeyMaker;

use Exception;
use Fruitsbytes\PHP\Moncash\Retry;
use Fruitsbytes\PHP\Moncash\Task;

/**
 * Uses PHP  [uniqid()](https://www.php.net/manual/en/function.uniqid.php) to generate  a
 * uniq number and stores it in a temporary file. It is good for tests and is not commanded
 * for real life applications, especially in the case of distributed servers.
 *
 * - ⚠ **Caution** This function does not generate cryptographically secure values and presents risks of collisions
 * - ⚠ **Caution** The temporary files can be cleared at anytime by a garbage collector or when the service is closed.
 *   The `./temp` directory may not be available to the web server.
 *
 */
class SimpleIdempotenceKeyMaker implements IdempotenceKeyMakerInterface
{

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->check();
    }

    /**
     * @inheritDoc
     */
    public function check(): bool
    {

        foreach (['uniqid', 'rand', 'touch'] as $fn) {
            if ( ! function_exists($fn)) {
                throw new IdempotenceException("Missing required function $fn");
            }
        }

        try {
            $this->logID('monCash_file_test');
        } catch (Exception $e) {
            throw new IdempotenceException("Could not write to temp folder.");
        }

        return true;
    }

    /**
     * @inheritDoc
     *
     * @param  bool  $verifyLocally  If true checks the logs to see if it was already used
     *
     */
    public function getNewID(bool $log = true, bool $verifyLocally = true): string
    {

        $id = null;

        try {
            $retry = new Retry(function () use ($verifyLocally, $log, &$id) {
                $found = uniqid(rand(), true);

                $path = self::nameToPath($found);

                if ($verifyLocally && file_exists($path)) {
                    throw new IdempotenceException('ID already used.');
                }
                if ($log && ! $this->logID($found)) {
                    throw new IdempotenceException('Could not log ID.');
                }

                $id = $found;
            });

            $retry->call();
        } catch (Exception $e) {
            throw new IdempotenceException('Error while generating ID.', 0, $e);
        }


        return $id;
    }

    /**
     * @inheritDoc
     */
    public function logID(string $id): bool
    {
        $path = self::nameToPath($id);

        try {
            touch($path);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function isIDAvailable(string $id): bool
    {
        $path = self::nameToPath($id);

        return ! file_exists($path);
    }

    /**
     *
     * Default TTL = 1 year
     * @inheritDoc
     *
     */
    public function cleanLog(int $ttl = 31536000): void
    {
        // TODO: Implement cleanLog() method.
    }

    /**@inheritDoc */
    function programCleaningTask(): Task
    {
        // TODO: Implement programCleaningTask() method.
        return new Task();
    }

    /**
     * @param  string  $name
     *
     * @return string
     */
    private static function nameToPath(string $name): string
    {
        $tempDir = sys_get_temp_dir();

        return "$tempDir/$name.key";
    }
}
