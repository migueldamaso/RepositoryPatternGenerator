<?php

use Paulo\Test\TestCase;
use Paulo\Test\TestUserContract;
use Paulo\Test\TestUser;
use Paulo\Exceptions\RepositoryException;
use Illuminate\Database\QueryException;

class RepositoryStoreTest extends TestCase
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
     * Store a user with a fake email
     *
     * @return mixed
     */
    protected function storeFakeEmail()
    {
        return $this->testUserRepository->store(
            ['email' => Faker\Factory::create()->email]
        );
    }

    /**
     * Test if the store method creates a new user
     *
     * @test
     **/
    public function it_stores_data()
    {
        $fakeEmail = Faker\Factory::create()->email;

        $this->testUserRepository->store([
            'email' => $fakeEmail
        ]);

        $this->assertTrue(TestUser::where('email', $fakeEmail)->exists());
    }

    /**
     * Test if the store methods throws an exception
     * whe incorrect data is provided
     *
     * @test
     **/
    public function it_throws_exception_when_incorrect_data_is_provided()
    {
        $this->expectException(QueryException::class);
        $this->testUserRepository->store([]);
    }

    /**
     * Test if the store method does not throw an error when the action property
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
     * Test if the store method does not throw an error when the action property
     * is store
     *
     * @test
     **/
    public function does_not_throw_a_exception_when_the_action_is_store()
    {
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $property->setValue($this->testUserRepository, ["store"]);

        $this->storeFakeEmail();

        $this->assertTrue(true);
    }


    /**
     * Test if the store method does throw an error the action property
     * is not store
     *
     * @test
     **/
    public function does_throw_an_exception_when_the_action_is_not_store()
    {
        $this->expectException(RepositoryException::class);
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $property->setValue($this->testUserRepository, ["post"]);

        $this->storeFakeEmail();
    }
}
