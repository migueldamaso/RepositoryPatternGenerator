<?php

namespace Paulo\Repositories;

use Paulo\Exceptions\RepositoryException;

trait Repository
{
    /**
     * @var string model.
     */
    private $model;

    /**
     * @var array allowed actions.
     */
    private $actions = [ '*' ];

    /**
     * Get's by it's ID
     *
     * @param int
     */
    public function get(int $id)
    {
        return $this->model::find($id);
    }

    /**
     * Get's all.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->model::all();
    }

    /**
     * Stores.
     *
     * @param int
     */
    public function store($data)
    {
        return $this->model::create($data);
    }

    /**
     * Deletes.
     *
     * @param int
     */
    public function delete(int $id)
    {
        $this->model::destroy($id);
    }

    /**
     * Updates.
     *
     * @param int
     * @param array
     */
    public function update(int $id, array $data)
    {
        return $this->model::find($id)->update($data);
    }

    /**
     * Check if the current called action in in available actions. 
     *
     * @param string $action current called method.
     * @throws \App\Exceptions\RepositoryException
     * @return void
     */
    private function decorateAction(string $action = '*')
    {
        if ($this->actions[0] != '*' && !in_array($action, $this->actions)) {
            throw new RepositoryException($action);
        }
    }

    /**
     * Apply decorator pattern.
     *
     * @param string $name
     * @param $arguments
     */
    public function __call(string $name, $arguments)
    {
        $this->decorateAction($name);

        if (method_exists($this, $name)) {
            $this->{$name}();
        }
    }
}
