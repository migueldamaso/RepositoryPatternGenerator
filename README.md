# Laravel Repository Pattern Generator

Generates repositories for laravel applications friendly.

usage:

Register provider in config/app.php
```
 Paulo\RepositoryServiceProvider::class,
```

Publish config file:
```
php artisan vendor:publish --provider="Paulo\RepositoryServiceProvider"
```

Generating repositories:

You can generate repositories with a artisan make, note that the repository should have the same name as model, otherwise it will be created wrong.
```
// generating repository for post model
php artisan make:repository Post
```
A folder will be created under name Repositories and will store all the repos.

After generating a new repository you need to register it on config/repositories.php.
```
'repositories' => [
    // Register here new repositories ...
     App\Repositories\PostRepository\PostRepositoryContract::class => App\Repositories\PostRepository\PostRepository::class,
 ],
```

or you can simply set skip import to true in repository.php config and load the repos on the fly without register them.
```
'skip_import' => true,
```

Finally withing your controller you can start to inject the repositories:
```
<?php

use App\Repositories\PostRepository\PostRepositoryContract as Repository;

class PostController extends Controller
{

    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
         $posts = $this->repository->all();
            
         return response()->json(compact('posts'));
     }
}
```

If you want to configure what actions the repository can do, you can add actions array property on you generated file and specify a list of allowed actions.
```
$this->actions = [ 'all', 'get' ];
```

If you try to call a non listed action a "RepositoryException" will be thrown.

If you want to change the location of the imports just change the namespace in repository.php config.
```
'namespace' => 'App\\',
```

Test:
```
composer test
```
