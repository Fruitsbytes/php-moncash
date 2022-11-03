<?php

namespace Fruitsbytes\PHP\Moncash;

use Exception;
use Throwable;

class  MonCashException extends Exception
{
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
        private mixed $data = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }
}
