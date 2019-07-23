<?php

namespace Paulo\Exceptions;

use Exception;

class RepositoryException extends Exception
{
    protected $message = 'Action "%s" is not in actions array.';

    public function __construct(string $action = '')
    {
        $this->message = sprintf($this->message, $action);
    }
}
