<?php

namespace Paulo\Test;

interface TestUserContract
{
    /**
     * Get's a post by it's ID
     *
     * @param int $id
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
     * @param array $data
     */
    public function store(array $data);

    /**
     * Deletes a post.
     *
     * @param int $id
     */
    public function delete(int $id);

    /**
     * Updates a post.
     *
     * @param int   $id
     * @param array $data
     */
    public function update(int $id, array $data);
}
