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
        if (File::exists(config_path('rspository.php'))) {

            if (config('repository.skip_import') == true) {
                (new RepositoryLoader)->loadRepositoriesDinamically();
            }

            $this->singletons = config('repository.repositories');
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
            __DIR__ . '/config/repository.php' => config_path('repository.php'),
        ]);

        $this->commands([
            MakeRepository::class,
            MakeRepositoryContract::class,
        ]);
    }
}
