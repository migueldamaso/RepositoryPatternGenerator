<?php

namespace Paulo\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use Paulo\RepositoryServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;

abstract class TestCase extends Orchestra
{
    /**
     * Setup for testing
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    /**
     * Register services provideros
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            RepositoryServiceProvider::class
        ];
    }

    /**
     * Setup the environment for testing
     *
     * @param  Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set(
            'database.connections.sqlite',
            [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ]
        );
        $app->singleton(TestUserContract::class, TestUserRepository::class);
    }

    /**
     * Add tables required for testing
     *
     * @param  Application $app
     *
     * @return void
     */
    protected function setUpDatabase(Application $app): void
    {
        $app['db']
            ->connection()
            ->getSchemaBuilder()
            ->create(
                'test_users',
                function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('email');
                    $table->timestamps();
                }
            );
    }
}
