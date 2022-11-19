<?php

namespace Fruitsbytes\PHP\MonCash\API;

use Exception;
use Throwable;

class  APIException extends Exception
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
