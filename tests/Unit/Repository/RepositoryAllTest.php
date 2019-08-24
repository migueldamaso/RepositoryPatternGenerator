<?php

use Paulo\Test\TestCase;
use Paulo\Test\TestUserContract;
use Paulo\Test\TestUser;
use Paulo\Exceptions\RepositoryException;
use Faker\Factory as FakerFactory;

class RepositoryAllTest extends TestCase
{
    /**
     * @var \Paulo\Test\TestUserRepository
     */
    protected $testUserRepository;

    /**
     * Setup user repository for tests
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->testUserRepository = $this->app->make(TestUserContract::class);
    }

    /**
     * Test if the all method returns a collection when it exists
     *
     * @test
     **/
    public function returns_collection_when_it_exists()
    {
        $email = FakerFactory::create()->email;

        TestUser::create(['email' => $email]);
        TestUser::create(['email' => $email]);

        $testUserCollection = $this->testUserRepository->all();

        $this->assertEquals($testUserCollection->count(), 2);
    }

    /**
     * Test if the all method returns an empty collection
     * when it does not exist
     *
     * @test
     */
    public function returns_empty_collection_when_it_does_not_exist()
    {
        $emptyTestUserCollection = $this->testUserRepository->all();

        $this->assertEquals($emptyTestUserCollection->count(), 0);
    }

    /**
     * Test if the all method does not throw an error when the action property
     * is a wildcard
     *
     * @test
     */
    public function does_not_throw_a_exception_when_the_action_is_a_wildcard()
    {
        $this->testUserRepository->all();

        $this->assertTrue(true);
    }

    /**
     * Test if the all method does not throw an error when the action property
     * is all
     *
     * @test
     **/
    public function does_not_throw_a_exception_when_the_action_is_all()
    {
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $property->setValue($this->testUserRepository, ["all"]);

        $this->testUserRepository->all();

        $this->assertTrue(true);
    }

    /**
     * Test if the all method does throw an error the action property
     * is not all
     *
     * @test
     **/
    public function throws_an_exception_when_the_action_is_not_all()
    {
        $this->expectException(RepositoryException::class);
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $property->setValue($this->testUserRepository, ["post"]);

        $this->testUserRepository->all();
    }
}
