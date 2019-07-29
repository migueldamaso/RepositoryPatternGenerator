<?php

namespace Paulo\Test;

use Paulo\Repositories\Repository;

final class TestUserRepository implements TestUserContract
{
    use Repository;

    public function __construct()
    {
        $this->model = TestUser::class;
    }
}
