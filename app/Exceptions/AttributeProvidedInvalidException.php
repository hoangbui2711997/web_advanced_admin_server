<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class AttributeProvidedInvalidException extends Exception
{
    //
    public function __construct()
    {
        parent::__construct('Attribute provided invalid');
    }
}
