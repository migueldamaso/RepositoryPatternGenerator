<?php

use Paulo\Test\TestCase;
use Paulo\Test\TestUserContract;
use Paulo\Test\TestUser;
use Paulo\Exceptions\RepositoryException;

class RepositoryGetTest extends TestCase
{
    protected $testUserRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->testUserRepository = $this->app->make(TestUserContract::class);
    }

    /** @test */
    public function returns_model_when_it_exists()
    {
        $testUser = TestUser::create(['email' => Faker\Factory::create()->email]);

        $repositoryTestUser = $this->testUserRepository->get($testUser->id);

        $this->assertEquals($testUser->toArray(), $repositoryTestUser->toArray());
    }

    /** @test */
    public function returns_null_when_it_does_not_exist()
    {
        $repositoryTestUser = $this->testUserRepository->get(1);

        $this->assertNull($repositoryTestUser);
    }

    /** @test */
    public function does_not_throw_a_exception_when_the_action_is_a_wildcard()
    {
        $this->testUserRepository->get(1);

        $this->assertTrue(true);
    }

    /** @test */
    public function does_not_throw_a_exception_when_the_action_is_get()
    {
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $property->setValue($this->testUserRepository, ["get"]);

        $this->testUserRepository->get(1);

        $this->assertTrue(true);
    }

    /** @test */
    public function does_throws_an_exception_when_the_action_is_not_get()
    {
        $this->expectException(RepositoryException::class);
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $property->setValue($this->testUserRepository, ["post"]);

        $this->testUserRepository->get(1);
    }
}
