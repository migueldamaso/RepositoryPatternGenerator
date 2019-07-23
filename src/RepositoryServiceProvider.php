<?php

namespace Paulo\Providers;

use Illuminate\Support\ServiceProvider;
use Paulo\Commands\{MakeRepository, MakeRepositoryContract};

final class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public $singletons = [];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->singletons = config('respository.repositories');
    }

    /**
     * Boot services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/respository.php' => config_path('respository.php'),
        ]);

        $this->commands([
            MakeRepository::class,
            MakeRepositoryContract::class,
        ]);
    }
}
