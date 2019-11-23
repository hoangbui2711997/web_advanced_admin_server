<?php

namespace App\Exceptions;

use Exception;

class QueueBusyException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('file.queue_busy'));
    }
}
