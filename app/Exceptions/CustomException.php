<?php

namespace App\Exceptions;

interface CustomException
{
    public function getMessage();
    public function getCode();
}
