<?php

namespace Paulo\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class MakeRepository extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    /**
	 * The type of class being generated.
	 *
	 * @var string
	 */
    protected $type = 'Repository';

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $this->createContract();

        $stub = str_replace(
            [
                'DummyNamespace',
                'DummyRepository',
                'DummyContract',
                'DummyClass',
                'DummyModelImport',
            ],
            [
                $name . 'Repository',
                $this->generateRepositoryName(),
                $this->generateContract($this->argument('name')),
                $this->generateModel(),
                $this->generateModelImpoert(),
        ],
            $stub
        );

        return $this;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            [ 'name', InputArgument::REQUIRED, 'The name of the repository.' ],
            [ 'model', InputArgument::REQUIRED, 'The name of the model.' ],
        ];
    }

	/**
	 * Get the stub file for the generator.
	 *
	 * @return string
	 */
	protected function getStub()
	{
		return __DIR__ . '/Stubs/make-repository.stub';
    }

	/**
	 * Get the default namespace for the class.
	 *
	 * @param  string  $rootNamespace
	 * @return string
	 */
	protected function getDefaultNamespace($rootNamespace)
	{
		return $rootNamespace . '\Repositories';
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace('DummyRepository', $this->argument('name'), $stub);
    }

    /**
     * Generate repository name.
     *
     * @return string
     */
    protected function generateRepositoryName()
    {
        return ucfirst(strtolower($this->argument('name'))) . 'Repository';
    }

    /**
     * Get the interface class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function generateContract($name)
    {
        return ucfirst(strtolower($name)) . 'RepositoryContract';
    }

    /**
     * Get the modal class name.
     *
     * @return string
     */
    protected function generateModel()
    {
        $model = $this->argument('name');
        $model = ucfirst(strtolower($model));

        return $model;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $name = str_replace('Repositories', '', $name);

        $repo = $this->argument('name') . 'Repository';

        return $this->laravel['path'] . '/Repositories/' . $repo . '/' . str_replace('\\', '/', $name) . 'Repository.php';
    }

    protected function generateModelImpoert()
    {
        return 'App\\' . $this->generateModel();
    }

    /**
     * Generate contract.
     * Call command.
     * 
     * @return void
     */
    private function createContract()
    {
        $this->call('make:contract', [
            'name' => "{$this->argument('name')}",
        ]);
    }
}
