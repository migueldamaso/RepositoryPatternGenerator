<?php

use Paulo\Test\TestCase;
use Paulo\Test\TestUserContract;
use Paulo\Test\TestUser;
use Paulo\Exceptions\RepositoryException;

class RepositoryDecorateActionTest extends TestCase
{
    /**
     * @var \Paulo\Test\TestUserRepository
     */
    protected $testUserRepository;

    /**
     * @var ReflectionProperty
     */
    protected $userRepositoryAction;

    /**
     * Initialize the test user repository and the reflection
     * class for testing
     *
     * @return void
     */
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

    /**
     * Test if actions pass if the actions property is a wildcard
     *
     * @test
     **/
    public function passes_when_actions_has_wildcard()
    {
        $this->userRepositoryAction->setValue($this->testUserRepository, ['*']);

        $this->userRepositoryActionDecorate->invoke($this->testUserRepository, 'post');

        $this->assertTrue(true);
    }

    /**
     * Test if actions pass if the actions property has
     * the invoked method name
     *
     * @test
     **/
    public function passes_when_decorate_actions_has_existing_action()
    {
        $this->userRepositoryAction->setValue($this->testUserRepository, ['post']);

        $this->userRepositoryActionDecorate->invoke($this->testUserRepository, 'post');

        $this->assertTrue(true);
    }

    /**
     * Test if an exception is thrown when the actions property
     * does not have the invoked method name
     *
     * @test
     **/
    public function throw_exception_when_decorate_actions_has_unexistent_action()
    {
        $this->expectException(RepositoryException::class);
        $this->userRepositoryAction->setValue($this->testUserRepository, ['post']);

        $this->userRepositoryActionDecorate->invoke($this->testUserRepository, 'get');
    }
}
