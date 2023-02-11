<?php

namespace App\Exceptions;

use Exception;

class UnableToCreateOrder extends Exception implements CustomException
{
    public function __construct($message = "Unable to create order.", $code = 500)
    {
        parent::__construct($message, $code);
    }
}
