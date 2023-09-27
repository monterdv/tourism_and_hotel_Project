<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CustomException extends Exception
{
    private $errorData;

    public function __construct($errorData, int $code = 0, Throwable $previous = null)
    {
        parent::__construct('', $code, $previous);
        $this->errorData = $errorData;
    }

    public function getErrorData()
    {
        return $this->errorData;
    }
}
