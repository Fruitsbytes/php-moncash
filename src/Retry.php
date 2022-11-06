<?php

namespace Fruitsbytes\PHP\MonCash;

use Closure;
use Exception;

class Retry
{


    /**
     * Retry a closure until it returns true for a number of attempts
     *
     * @param  Closure  $closure  closure containing the operation that need to be retried
     * @param  int<2,10>  $attempts  Number of attempts, maximum 10
     *
     * @throws APIException
     */
    public function __construct(public Closure $closure, public int $attempts = 3)
    {
        if ($this->attempts > 10 || $this->attempts < 2) {
            throw new APIException(
                $this->attempts > 10 ?
                    'Max number of attempts is 10' :
                    'Min number of attempts is 2 else its does not need to be retried');
        }
    }


    /**
     * @throws APIException
     */
    public function call(): void
    {
        $failures  = 0;
        $succeeded = false;
        $exception = null;

        do {
            try {
               $succeeded = $this->closure->call($this);
            } catch (Exception $e) {
                $exception = $e;
            }
            $failures++;
        } while ($failures < $this->attempts && $succeeded !== true);

        if ($succeeded !== true) {
            throw new APIException('Max number of attempts  exceeded for this call', 0, $exception);
        }
    }

}
