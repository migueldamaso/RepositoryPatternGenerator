<?php

namespace Paulo\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeRepositoryContract extends GeneratorCommand
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
    protected $name = 'make:contract';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
    protected $description = 'Create a new contract interface';

	/**
	 * The type of class being generated.
	 *
	 * @var string
	 */
    protected $type = 'Contract';

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

        return str_replace('DummyContract',	$this->generateContract($this->argument('name')), $stub);
	}
	
	/**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace('DummyNamespace', $this->generateNamespace($name), $stub);

        return $this;
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
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the contract.'],
        ];
	}

	protected function generateNamespace($name)
	{
		return $name . 'Repository';
	}

	/**
     * Generate contract impoert.
     *
     * @return string
     */
	protected function generateContract($name)
    {
        return ucfirst(strtolower($name)) . 'RepositoryContract';
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
        
		return $this->laravel['path'] . '/Repositories/' . $repo . '/' . str_replace('\\', '/', $name) . 'RepositoryContract.php';
    }
}
