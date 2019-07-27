<?php

namespace Paulo\Test;

interface TestUserContract
{
    /**
     * Get's a post by it's ID
     *
     * @param int
     */
    public function get(int $id);

    /**
     * Get's all posts.
     *
     * @return mixed
     */
    public function all();

    /**
     * Stores a post.
     *
     * @param mixed
     */
    public function store($data);

    /**
     * Deletes a post.
     *
     * @param int
     */
    public function delete(int $id);

    /**
     * Updates a post.
     *
     * @param int
     * @param array
     */
    public function update(int $id, array $data);
}
