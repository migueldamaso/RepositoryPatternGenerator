<?php

use Paulo\Test\TestCase;
use Paulo\Test\TestUserContract;
use Paulo\Test\TestUser;
use Paulo\Exceptions\RepositoryException;

class RepositoryDeleteTest extends TestCase
{
    protected $testUserRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->testUserRepository = $this->app->make(TestUserContract::class);
    }

    public function createMockUser(): TestUser
    {
        return TestUser::create(['email' => Faker\Factory::create()->email]);
    }

    /** @test */
    public function deletes_existing_user()
    {
        $testUser = $this->createMockUser();

        $this->testUserRepository->delete($testUser->id);

        $this->assertNull(TestUser::find($testUser->id));
    }

    /** @test */
    public function does_not_throw_a_exception_when_the_action_is_a_wildcard()
    {
        $testUser = $this->createMockUser();

        $this->testUserRepository->delete($testUser->id);

        $this->assertTrue(true);
    }

    /** @test */
    public function does_not_throw_a_exception_when_the_action_is_delete()
    {
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $property->setValue($this->testUserRepository, ["delete"]);

        $testUser = $this->createMockUser();

        $this->testUserRepository->delete($testUser->id);

        $this->assertTrue(true);
    }

    /** @test */
    public function does_throws_an_exception_when_the_action_is_not_delete()
    {
        $this->expectException(RepositoryException::class);
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $property->setValue($this->testUserRepository, ["post"]);

        $testUser = $this->createMockUser();

        $this->testUserRepository->delete($testUser->id);
    }
}
