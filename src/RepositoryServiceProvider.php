<?php

namespace Paulo;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Paulo\Classes\RepositoryLoader;
use Paulo\Console\Commands\{MakeRepository, MakeRepositoryContract};

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
        if (File::exists(config_path('respository.php'))) {

            if (config('respository.skip_import') == true) {
                (new RepositoryLoader)->loadRepositoriesDinamically();
            }

            $this->singletons = config('respository.repositories');
        }
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
