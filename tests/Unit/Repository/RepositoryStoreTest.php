<?php

use Paulo\Test\TestCase;
use Paulo\Test\TestUserContract;
use Paulo\Test\TestUser;
use Paulo\Exceptions\RepositoryException;
use Illuminate\Database\QueryException;

class RepositoryStoreTest extends TestCase
{
    protected $testUserRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->testUserRepository = $this->app->make(TestUserContract::class);
    }

    protected function storeFakeEmail()
    {
        return $this->testUserRepository->store([
            'email' => Faker\Factory::create()->email
        ]);
    }

    /** @test */
    public function it_stores_data()
    {
        $fakeEmail = Faker\Factory::create()->email;

        $this->testUserRepository->store([
            'email' => $fakeEmail
        ]);

        $this->assertTrue(TestUser::where('email', $fakeEmail)->exists());
    }

    /** @test */
    public function it_throws_exception_when_incorrect_data_is_provided()
    {
        $this->expectException(QueryException::class);
        $this->testUserRepository->store([]);
    }

    /** @test */
    public function does_not_throw_a_exception_when_the_action_is_a_wildcard()
    {
        $this->testUserRepository->get(1);

        $this->assertTrue(true);
    }

    /** @test */
    public function does_not_throw_a_exception_when_the_action_is_store()
    {
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $property->setValue($this->testUserRepository, ["store"]);

        $this->storeFakeEmail();

        $this->assertTrue(true);
    }

    /** @test */
    public function does_throws_an_exception_when_the_action_is_not_store()
    {
        $this->expectException(RepositoryException::class);
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $property->setValue($this->testUserRepository, ["post"]);

        $this->storeFakeEmail();
    }
}
