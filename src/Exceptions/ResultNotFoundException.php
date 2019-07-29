<?php

namespace Paulo\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;

class ResultNotFoundException extends Exception
{
    protected $message = 'Id %s was not found in the %s table';

    public function __construct(Model $model, int $id)
    {
        $this->message = sprintf(
            $this->message,
            $model->getTable(),
            $id
        );
    }
}
