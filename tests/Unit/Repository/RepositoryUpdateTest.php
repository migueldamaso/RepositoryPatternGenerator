<?php

use Paulo\Test\TestCase;
use Paulo\Test\TestUserContract;
use Paulo\Test\TestUser;
use Paulo\Exceptions\RepositoryException;
use Illuminate\Database\QueryException;
use Paulo\Exceptions\ResultNotFoundException;

class RepositoryUpdateTest extends TestCase
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
        return TestUser::create([
            'email' => Faker\Factory::create()->email
        ]);
    }

    /**
     * Test if the update method updates database data
     *
     * @test
     **/
    public function it_updates_data()
    {
        $testUser = $this->storeFakeEmail();

        $fakeEmail = Faker\Factory::create()->email;

        $this->testUserRepository->update($testUser->id, [
            'email' => $fakeEmail
        ]);

        $this->assertTrue(
            TestUser::where([
                'email' => $fakeEmail,
                'id' => $testUser->id
            ])->exists()
        );
    }

    /**
     * Test if the update method throws an exception when the
     * provided id does not exist
     *
     * @test
     **/
    public function it_throws_exception_when_id_does_not_exist()
    {
        $this->expectException(ResultNotFoundException::class);

        $this->testUserRepository->update(4, []);
    }

    /**
     * Test if the update method does not throw an error when the action property
     * is a wildcard
     *
     * @test
     */
    public function does_not_throw_a_exception_when_the_action_is_a_wildcard()
    {
        $testUser = $this->storeFakeEmail();

        $fakeEmail = Faker\Factory::create()->email;

        $this->testUserRepository->update($testUser->id, [
            'email' => $fakeEmail
        ]);

        $this->assertTrue(true);
    }

    /**
     * Test if the update method does not throw an error when the action property
     * is update
     *
     * @test
     **/
    public function does_not_throw_a_exception_when_the_action_is_update()
    {
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $property->setValue($this->testUserRepository, ["update"]);

        $testUser = $this->storeFakeEmail();

        $fakeEmail = Faker\Factory::create()->email;

        $this->testUserRepository->update($testUser->id, [
            'email' => $fakeEmail
        ]);

        $this->assertTrue(true);
    }

    /**
     * Test if the update method does throw an error the action property
     * is not update
     *
     * @test
     **/
    public function does_throw_an_exception_when_the_action_is_not_update()
    {
        $this->expectException(RepositoryException::class);
        $class = new ReflectionClass($this->testUserRepository);
        $property = $class->getProperty("actions");
        $property->setAccessible(true);
        $property->setValue($this->testUserRepository, ["post"]);

        $testUser = $this->storeFakeEmail();

        $fakeEmail = Faker\Factory::create()->email;

        $this->testUserRepository->update($testUser->id, [
            'email' => $fakeEmail
        ]);
    }
}
