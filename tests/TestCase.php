<?php

namespace Paulo\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use Paulo\RepositoryServiceProvider;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders($app)
    {
        return [
            RepositoryServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app->singleton(TestUserContract::class, TestUserRepository::class);
    }

    protected function setUpDatabase($app)
    {
        $app['db']
            ->connection()
            ->getSchemaBuilder()
            ->create('test_users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('email');
                $table->timestamps();
            });
    }
}
