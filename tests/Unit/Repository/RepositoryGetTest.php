<?php

use Paulo\Test\TestCase;
use Paulo\Test\TestUserContract;
use Paulo\Test\TestUser;
use Paulo\Exceptions\RepositoryException;

class RepositoryGetTest extends TestCase
{
    /**
     * @var \Paulo\Test\TestUserRepository
     */
    protected $testUserRepository;

    /**
     * Setup the test user repository for testing
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->testUserRepository = $this->app->make(TestUserContract::class);
    }

    /**
     * Test if the get method returns an existing user
     *
     * @test
     **/
    public function returns_model_when_it_exists()
    {
        $testUser = TestUser::create(['email' => Faker\Factory::create()->email]);

        $repositoryTestUser = $this->testUserRepository->get($testUser->id);

        $this->assertEquals($testUser->toArray(), $repositoryTestUser->toArray());
    }

    /**
     * Test if the get method returns null if the selected
     * user does not exist
     *
     * @test
     **/
    public function returns_null_when_it_does_not_exist()
    {
        $repositoryTestUser = $this->testUserRepository->get(1);

        $this->assertNull($repositoryTestUser);
    }

    /**
     * Test if the get method does not throw an error when the action property
     * is a wildcard
     *
     * @test
     */
    public function does_not_throw_a_exception_when_the_action_is_a_wildcard()
    {
        $this->testUserRepository->get(1);

        $this->assertTrue(true);
    }

    /**
     * Test if the get method does not throw an error when the action property
     * is get
     *
     * @test
     **/
    public function does_not_throw_a_exception_when_the_action_is_get()
    {
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $property->setValue($this->testUserRepository, ["get"]);

        $this->testUserRepository->get(1);

        $this->assertTrue(true);
    }

    /**
     * Test if the get method does throw an error the action property
     * is not get
     *
     * @test
     **/
    public function does_throw_an_exception_when_the_action_is_not_get()
    {
        $this->expectException(RepositoryException::class);
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $property->setValue($this->testUserRepository, ["post"]);

        $this->testUserRepository->get(1);
    }
}
