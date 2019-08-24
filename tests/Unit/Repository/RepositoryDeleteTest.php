<?php

use Paulo\Test\TestCase;
use Paulo\Test\TestUserContract;
use Paulo\Test\TestUser;
use Paulo\Exceptions\RepositoryException;

class RepositoryDeleteTest extends TestCase
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
     * Generates a mock user
     *
     * @return TestUser
     */
    public function createMockUser(): TestUser
    {
        return TestUser::create(['email' => Faker\Factory::create()->email]);
    }

    /**
     * It deletes an existing user
     *
     * @test
     **/
    public function deletes_existing_user()
    {
        $testUser = $this->createMockUser();

        $this->testUserRepository->delete($testUser->id);

        $this->assertNull(TestUser::find($testUser->id));
    }

    /**
     * Test if the delete method does not throw an error when the action property
     * is a wildcard
     *
     * @test
     */
    public function does_not_throw_a_exception_when_the_action_is_a_wildcard()
    {
        $testUser = $this->createMockUser();

        $this->testUserRepository->delete($testUser->id);

        $this->assertTrue(true);
    }


    /**
     * Test if the delete method does not throw an error when the action property
     * is delete
     *
     * @test
     **/
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

    /**
     * Test if the delete method does throw an error the action property
     * is not delete
     *
     * @test
     **/
    public function does_throw_an_exception_when_the_action_is_not_delete()
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
