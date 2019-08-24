<?php

namespace Paulo\Test;

use Illuminate\Database\Eloquent\Model;

class TestUser extends Model
{
    /**
     * @var array
     */
    public $fillable = [
        'email'
    ];
}
