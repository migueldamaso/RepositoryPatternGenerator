<?php

use Paulo\Test\TestCase;
use Paulo\Test\TestUserContract;
use Paulo\Test\TestUser;
use Paulo\Exceptions\RepositoryException;

class RepositoryDecorateActionTest extends TestCase
{
    protected $testUserRepository;

    protected $userRepositoryAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->testUserRepository = $this->app->make(TestUserContract::class);
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $this->userRepositoryAction = $property;

        $method = $class->getMethod('decorateAction');
        $method->setAccessible(true);
        $this->userRepositoryActionDecorate = $method;
    }

    /** @test */
    public function passes_when_actions_has_wildcard()
    {
        $this->userRepositoryAction->setValue($this->testUserRepository, ['*']);

        $this->userRepositoryActionDecorate->invoke($this->testUserRepository, 'post');

        $this->assertTrue(true);
    }

    /** @test */
    public function passes_when_decorate_actions_has_existing_action()
    {
        $this->userRepositoryAction->setValue($this->testUserRepository, ['post']);

        $this->userRepositoryActionDecorate->invoke($this->testUserRepository, 'post');

        $this->assertTrue(true);
    }

    /** @test */
    public function throws_exception_when_decorate_actions_has_unexistent_action()
    {
        $this->expectException(RepositoryException::class);
        $this->userRepositoryAction->setValue($this->testUserRepository, ['post']);

        $this->userRepositoryActionDecorate->invoke($this->testUserRepository, 'get');
    }
}
