<?php

namespace Fruitsbytes\PHP\MonCash\Strategy\OrderIdGenerator;

use Exception;
use Fruitsbytes\PHP\MonCash\API\Retry;
use Fruitsbytes\PHP\MonCash\Task;

/**
 * Uses PHP  [uniqid()](https://www.php.net/manual/en/function.uniqid.php) to generate  a
 * uniq number and stores it in a **temporary file**. It is good for tests and is not recommended
 * for real life applications, especially in the case of distributed servers.
 *
 * - ⚠ **Caution** This function does not generate cryptographically secure values and presents risks of collisions
 * - ⚠ **Caution** The temporary files can be cleared at anytime by a garbage collector or when the service is closed.
 *   The `./temp` directory may not be available to the web server.
 *
 */
class SimpleOrderIdGenerator implements OrderIdGeneratorInterface
{

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $this->check();
    }

    /**
     * @param  bool $thorough Add extensive test that may be memory intensive.
     * @inheritDoc
     */
    public function check( bool $thorough = false): bool
    {

        /**
         * Check if all required PHP functions are available.
         * - Some Host may be using a trimmed down version of PHP.
         * - In the future one of the function may be deprecated/removed
         */
        foreach (['uniqid', 'rand', 'touch', 'unlink', 'filectime', 'strtotime'] as $fn) {
            if (function_exists($fn) === false) {
                throw new OrderIdGeneratorException("Missing required function $fn");
            }
        }

        if ($thorough) {
            try {
                $this->logID('monCash_file_test');
            } catch (Exception $e) {
                throw new OrderIdGeneratorException("Could not write to temp folder.", 0, $e);
            }
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
                    throw new OrderIdGeneratorException('ID already used.');
                }
                if ($log && ! $this->logID($found)) {
                    throw new OrderIdGeneratorException('Could not log ID.');
                }

                $id = $found;
            });

            $retry->call();
        } catch (Exception $e) {
            throw new OrderIdGeneratorException('Error while generating ID.', 0, $e);
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
    public function cleanLog(int $ttl = 31536000): int
    {
        $files         = scandir(sys_get_temp_dir());
        $numberOfFiles = 0;

        foreach ($files as $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if ($ext === 'i') {
                $creationDate = filectime($file);
                $cursor       = strtotime("-$ttl second");

                if ($cursor > $creationDate) {
                    try {
                        unlink($file);
                        $numberOfFiles++;
                    } catch (Exception) {
                        //
                    }
                }
            }
        }

        return $numberOfFiles;
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
    public static function nameToPath(string $name): string
    {
        $tempDir = sys_get_temp_dir();

        return "$tempDir/$name.i";
    }
}
